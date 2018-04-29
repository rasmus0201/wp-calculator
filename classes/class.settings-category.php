<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Category settings page / data
 */
class Bundsgaard_Settings_Category extends Bundsgaard_Settings implements Bundsgaard_Settings_Template
{
    protected $type = 'category';

    function __construct(){

    }

    public function hasPost(){
        if (isset($_POST['bc_post_category'])) {
            $this->post();
        } else if (isset($_POST['bc_patch_category'])) {
            $this->patch();
        } else if (isset($_POST['bc_delete_category'])) {
            $this->delete();
        }
    }

    public function post(){
        $name = trim($_POST['bc_name']);
        $parent_id = $_POST['bc_parent'];

        $options = $this->getOptions($this->type);
        $next_id = $this->getNextId($this->type);
        $options = !is_null($options) ? $options : [];

        $options[] = [
            'id' => $next_id,
            'name' => $name,
            'parent' => $parent_id,
        ];

        $this->updateOptions($this->type, $options);
        $this->updateIds($this->type, $next_id+1);
    }

    public function patch(){
        $name = trim($_POST['bc_name']);
        $selected_id = $_POST['bc_id'];
        $parent_id = $_POST['bc_parent'];

        $options = $this->getOptions($this->type);


        if ($parent_id >= 0) {

            $has_children = false;

            //Check if current updated has children, and wants to change to a child
            foreach ($options as $category) {
                if ($category['parent'] == $selected_id) {
                    $has_children = true;

                    break;
                }
            }

            if (!$has_children) {
                foreach ($options as $key => $category) {
                    if ($category['id'] == $selected_id) {
                        $options[$key]['name'] = $name;
                        $options[$key]['parent'] = $parent_id;

                        break;
                    }
                }
            }
        } else {
            foreach ($options as $key => $category) {
                if ($category['id'] == $selected_id) {
                    $options[$key]['name'] = $name;
                    $options[$key]['parent'] = $parent_id;

                    break;
                }
            }
        }


        $this->updateOptions($this->type, $options);
    }

    public function delete(){
        $selected_id = $_POST['bc_id'];

        $options = $this->getOptions($this->type);

        //Update children to no parent_id
        foreach ($options as $key => $category) {
            if ($category['parent'] == $selected_id) {
                $options[$key]['parent'] = -1;
            }
        }

        //Delete element
        foreach ($options as $key => $category) {
            if ($category['id'] == $selected_id) {
                unset($options[$key]);

                break;
            }
        }

        $this->updateOptions($this->type, $options);
    }
}
