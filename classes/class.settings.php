<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Settings class
 */
class Bundsgaard_Settings
{

    function __construct()
    {
        # Code
    }

    public function getTemplate(){
        return include BUNDSGAARD_CALCULATOR_PATH.'/templates/admin/settings-'.$this->type.'.php';
    }

    public static function getOptions($type = 'product'){
        switch ($type) {
            case 'shipping':
                return get_option('bundsgaard_calculator_shipping', []);
                break;

            case 'category':
                return get_option('bundsgaard_calculator_categories', []);
                break;

            case 'color':
                return get_option('bundsgaard_calculator_colors', []);
                break;

            case 'product':
                return get_option('bundsgaard_calculator_products', []);
                break;

            default:
                return get_option('bundsgaard_calculator_products', []);
                break;
        }
    }

    public static function updateOptions($type = 'product', $data = []){
        switch ($type) {
            case 'shipping':
                update_option('bundsgaard_calculator_shipping', $data);
                return true;
                break;

            case 'category':
                update_option('bundsgaard_calculator_categories', $data);
                return true;
                break;

            case 'product':
                update_option('bundsgaard_calculator_products', $data);
                return true;
                break;

            case 'color':
                update_option('bundsgaard_calculator_colors', $data);
                return true;
                break;

            default:
                update_option('bundsgaard_calculator_products', $data);
                return true;
                break;
        }

        return false;
    }

    public static function getIds(){
        $ids = get_option('bundsgaard_calculator_ids');

        return (empty($ids)) ? [] : $ids;
    }

    public static function getNextId($type = 'product'){
        $ids = Bundsgaard_Settings::getIds();

        if ( !isset($ids[$type]) ) {
            $new_type = [$type => 0];

            update_option('bundsgaard_calculator_ids', array_merge($ids, $new_type));

            return 0;
        } else {
            return $ids[$type];
        }

        return;
    }

    public static function updateIds($type = 'product', $next_id = 0){
        $ids = Bundsgaard_Settings::getIds();

        if ( !isset($ids[$type]) ) {
            return false;
        }

        $ids[$type] = $next_id;
        update_option('bundsgaard_calculator_ids', $ids);

        return true;
    }

    public static function formatCategories(){
        $products = self::getOptions('product');
        $categories = self::getOptions('category');
        $colors = self::getOptions('color');

        if (empty($products)) {
            return [];
        }

        $uncategorized_products = [
            'id' => self::getNextId('category'),
            'name' => 'Ikke kategoriseret',
            'type' => 'category',
            'products' => [],
            'subcategories' => [],
        ];

        foreach ($products as $pr_key => $product) {
            $product[$pr_key]['type'] = 'product';
            $products[$pr_key]['colors'] = [];

            if (!empty($product['color_ids'])) {
                foreach ($product['color_ids'] as $co_key => $color_id) {
                    foreach ($colors as $co_pa_key => $color) {
                        if ($color['id'] == $color_id) {
                            $color['type'] = 'color';
                            $products[$pr_key]['colors'][] = $color;
                        }
                    }
                }
            }

            unset($products[$pr_key]['color_ids']);

            $found_category = false;

            if (!empty($categories)) {
                foreach ($categories as $ca_key => $category) {
                    if ($product['category'] == $category['id']) {
                        //move product into category
                        unset($products[$pr_key]['category']);

                        if (!isset($categories[$ca_key]['products'])) {
                            $categories[$ca_key]['products'] = [];
                        }

                        $categories[$ca_key]['products'][] = $products[$pr_key];

                        $found_category = true;

                        //A product can max have 1 category, så we break the loop
                        break;
                    }
                }
            }

            if (!$found_category) {
                //Add to uncategorized products
                unset($products[$pr_key]['category']);

                $uncategorized_products['products'][] = $products[$pr_key];
            }
        }


        $new_categories = [];

        if (!empty($categories)) {
            foreach ($categories as $ca_key => $category) {
                $categories[$ca_key]['type'] = 'category';

                if (!isset($categories[$ca_key]['products'])) {
                    $categories[$ca_key]['products'] = [];
                }

                if (!isset($categories[$ca_key]['subcategories'])) {
                    $categories[$ca_key]['subcategories'] = [];
                }

                if ($category['parent'] >= 0) {
                    foreach ($categories as $pa_ca_key => $pa_category) {
                        if ($category['parent'] == $pa_category['id']) {
                            $categories[$pa_ca_key]['subcategories'][] = [
                                'id' => $category['id'],
                                'name' => $category['name'],
                                'type' => 'subcategory',
                                'products' => $category['products'],
                            ];

                            unset($categories[$ca_key]);

                            break;
                        }
                    }
                } else {
                    unset($categories[$ca_key]['parent']);
                    $categories[$ca_key]['type'] = 'category';
                }
            }

            foreach ($categories as $ca_key => $category) {
                $new_categories[] = $categories[$ca_key];
            }
        }

        if (!empty($uncategorized_products['products'])) {
            $new_categories[] = $uncategorized_products;
        }

        return $new_categories;
    }

    public static function formatShipping(){
        return self::getOptions('shipping');
    }

    public static function deleteAll(){
        delete_option('bundsgaard_calculator_ids');

        delete_option('bundsgaard_calculator_products');
        delete_option('bundsgaard_calculator_colors');
        delete_option('bundsgaard_calculator_categories');
        delete_option('bundsgaard_calculator_shipping');
    }
}
