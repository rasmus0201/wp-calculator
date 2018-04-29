<?php
/*
Plugin Name: Prisberegner
Description:  Tilføjer prisberegneren som shortcode
Version:      1.0.0
Author:       Rasmus
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if (!defined('BUNDSGAARD_CALCULATOR_PATH')) {
    define('BUNDSGAARD_CALCULATOR_PATH', __DIR__);
}

/**
 *
 */
class Bundsgaard_Calculator
{
    /**
    * A Unique Identifier
    */
    protected $plugin_slug;

    /**
    * A reference to an instance of this class.
    */
    private static $instance;

    /**
    * Tabbed settings
    */
    private $tabs;


    private function __construct()
    {
        $this->plugin_slug = 'bundsgaard_calculator';
        $this->tabs = ['category' => 'Kategorier', 'color' => 'Farver', 'product' => 'Produkter', 'shipping' => 'Fragtmetoder'];

        $this->autoload_classes();

        add_action('admin_menu', array( $this, 'admin_menu' ));
        add_action('admin_init', array( $this, 'admin_init' ));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'));


        //shortcode
        add_shortcode('prisberegner', array(new Bundsgaard_Calculator_Shortcode(), 'shortcode'));
    }

    /**
    * Returns an instance of this class.
    */
    public static function get_instance() {

        if( null == self::$instance ) {
            self::$instance = new Bundsgaard_Calculator();
        }

        return self::$instance;
    }

    /**
    * Autoload the required classes
    */
    private function autoload_classes() {
        //1. Priority
        require_once 'classes/interface.settings.php';
        require_once 'classes/class.settings.php';

        //Autoload classes
        spl_autoload_register(function($c){
            $cd = getcwd();
            chdir(__DIR__.'/classes');
            $files = glob("*.php");

            foreach ($files as $file) {
                if (file_exists($file)){
                    require_once($file);
                }
            }

            chdir($cd);
        });
    }

    /**
    * Add a settings link to your WordPress plugin on the plugin listing page.
    */
    function add_action_links($links){
        $links[] = '<a href="' . admin_url( 'options-general.php?page='.$this->plugin_slug) . '">Indstillinger</a>';

        return $links;
    }

    /**
    * Admin init
    */
    public function admin_init(){
        global $pagenow;

        if ($pagenow == 'options-general.php' && $_GET['page'] == $this->plugin_slug) {
            $this->hasPost();
        }
    }

    private function hasPost(){
        (new Bundsgaard_Settings_Category())->hasPost();
        (new Bundsgaard_Settings_Shipping())->hasPost();
        (new Bundsgaard_Settings_Product())->hasPost();
        (new Bundsgaard_Settings_Color())->hasPost();
    }

    /**
    * Add a menu page on the settings menu
    */
    public function admin_menu() {
		add_options_page('Prisberegner', 'Prisberegner', 'manage_options', $this->plugin_slug, array( $this, 'settings_page'));
	}

    /**
    * Settings page content
    */
	public function settings_page() {
        ?>

        <div class="wrap">
            <h1>Prisberegner</h1>

            <p>For at bruge beregneren kan denne shortcode indsættes: <code>[prisberegner]</code></p>

            <?php
                //generic HTML and code goes here
                if ( isset ( $_GET['tab'] ) ) $this->settings_tabs($_GET['tab']); else $this->settings_tabs('category');

                $tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'category';

                switch ($tab) {
                    case 'category':
                        (new Bundsgaard_Settings_Category())->getTemplate();
                        break;

                    case 'product':
                        (new Bundsgaard_Settings_Product())->getTemplate();
                        break;

                    case 'color':
                        (new Bundsgaard_Settings_Color())->getTemplate();
                        break;

                    case 'shipping':
                        (new Bundsgaard_Settings_Shipping())->getTemplate();
                        break;

                    default:
                        (new Bundsgaard_Settings_Category())->getTemplate();
                        break;
                }
            ?>
        </div>
        <?php
	}

    public function settings_tabs( $current = 'category' ) {
        echo '<h2 class="nav-tab-wrapper">';

        foreach( $this->tabs as $tab => $name ){
            $active = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo '<a class="nav-tab'.$active.'" href="?page='.$this->plugin_slug.'&tab='.$tab.'">'.$name.'</a>';
        }

        echo '</h2>';
    }
}

add_action( 'plugins_loaded', array( 'Bundsgaard_Calculator', 'get_instance' ) );
