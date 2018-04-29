<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


/**
 * Product settings page / data
 */
class Bundsgaard_Settings_Product extends Bundsgaard_Settings implements Bundsgaard_Settings_Template
{
    protected $type = 'product';

    function __construct(){

    }

    public function hasPost(){
        if (isset($_POST['bc_post_product'])) {
            $this->post();
        } else if (isset($_POST['bc_patch_product'])) {
            $this->patch();
        } else if (isset($_POST['bc_delete_product'])) {
            $this->delete();
        }
    }

    public function post(){
        $name = trim($_POST['bc_name']);
        $category = trim($_POST['bc_category']);
        $price = trim($_POST['bc_price']);
        $discount_at = empty(trim($_POST['bc_qty_discount_at'])) ? 0 : trim($_POST['bc_qty_discount_at']);
        $discount_amount = empty(trim($_POST['bc_qty_discount_amount'])) ? 0 : trim($_POST['bc_qty_discount_amount']);
        $colors = isset($_POST['bc_colors']) ? $_POST['bc_colors'] : [];

        if (!is_numeric($price) && !is_float($price)) {
            return false;
        } else if (!is_numeric($discount_at)) {
            return false;
        } else if (!is_numeric($discount_amount) && !is_float($discount_amount)) {
            return false;
        }

        $discount_at = (int)$discount_at;

        $options = $this->getOptions($this->type);
        $next_id = $this->getNextId($this->type);
        $options = !is_null($options) ? $options : [];

        $options[] = [
            'id' => $next_id,
            'name' => $name,
            'price' => $price,
            'qty_discount' => ($discount_at > 0) ? true : false,
            'qty_discount_at' => $discount_at,
            'qty_discount_amount' => $discount_amount,
            'category' => $category,
            'color_ids' => $colors,
        ];

        $this->updateOptions($this->type, $options);
        $this->updateIds($this->type, $next_id+1);
    }

    public function patch(){
        $name = trim($_POST['bc_name']);
        $category = trim($_POST['bc_category']);
        $price = trim($_POST['bc_price']);
        $discount_at = empty(trim($_POST['bc_qty_discount_at'])) ? 0 : trim($_POST['bc_qty_discount_at']);
        $discount_amount = empty(trim($_POST['bc_qty_discount_amount'])) ? 0 : trim($_POST['bc_qty_discount_amount']);
        $colors = isset($_POST['bc_colors']) ? $_POST['bc_colors'] : [];
        $selected_id = $_POST['bc_id'];

        if (!is_numeric($price) && !is_float($price)) {
            return false;
        } else if (!is_numeric($discount_at)) {
            return false;
        } else if (!is_numeric($discount_amount) && !is_float($discount_amount)) {
            return false;
        }

        $discount_at = (int)$discount_at;

        $options = $this->getOptions($this->type);

        foreach ($options as $key => $product) {
            if ($product['id'] == $selected_id) {
                $options[$key] = [
                    'id' => $product['id'],
                    'name' => $name,
                    'price' => $price,
                    'qty_discount' => ($discount_at > 0) ? true : false,
                    'qty_discount_at' => $discount_at,
                    'qty_discount_amount' => $discount_amount,
                    'category' => $category,
                    'color_ids' => $colors,
                ];
            }
        }


        $this->updateOptions($this->type, $options);
    }

    public function delete(){
        $selected_id = $_POST['bc_id'];

        $options = $this->getOptions($this->type);

        //Delete element
        foreach ($options as $key => $product) {
            if ($product['id'] == $selected_id) {
                unset($options[$key]);

                break;
            }
        }

        $this->updateOptions($this->type, $options);
    }
}
