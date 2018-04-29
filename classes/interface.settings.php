<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Used for settings
 */
interface Bundsgaard_Settings_Template
{
    public function hasPost();
    public function post();
    public function patch();
    public function delete();

    //public static function getTemplate();
}
