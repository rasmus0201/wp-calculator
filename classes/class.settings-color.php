<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Color settings page / data
 */
class Bundsgaard_Settings_Color extends Bundsgaard_Settings implements Bundsgaard_Settings_Template
{
    protected $type = 'color';

    function __construct(){

    }

    public function hasPost(){
        if (isset($_POST['bc_post_color'])) {
            $this->post();
        } else if (isset($_POST['bc_patch_color'])) {
            $this->patch();
        } else if (isset($_POST['bc_delete_color'])) {
            $this->delete();
        }
    }

    public function post(){
        $name = trim($_POST['bc_name']);
        $price = $_POST['bc_price'];

        if (!is_numeric($price) && !is_float($price)) {
            return false;
        }

        $options = $this->getOptions($this->type);
        $next_id = $this->getNextId($this->type);
        $options = !is_null($options) ? $options : [];

        $options[] = [
            'id' => $next_id,
            'name' => $name,
            'price' => $price,
        ];

        $this->updateOptions($this->type, $options);
        $this->updateIds($this->type, $next_id+1);
    }

    public function patch(){
        $name = trim($_POST['bc_name']);
        $price = $_POST['bc_price'];
        $selected_id = $_POST['bc_id'];

        if (!is_numeric($price) && !is_float($price)) {
            return false;
        }

        $options = $this->getOptions($this->type);

        foreach ($options as $key => $color) {
            if ($color['id'] == $selected_id) {
                $options[$key]['price'] = $price;
                $options[$key]['name'] = $name;

                break;
            }
        }

        $this->updateOptions($this->type, $options);
    }

    public function delete(){
        $selected_id = $_POST['bc_id'];

        $options = $this->getOptions($this->type);

        foreach ($options as $key => $color) {
            if ($color['id'] == $selected_id) {
                unset($options[$key]);

                break;
            }
        }

        $this->updateOptions($this->type, $options);
    }
}
