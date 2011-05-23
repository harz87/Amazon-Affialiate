<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * makes sure that Zend Library is loaded
 */
function zff_load()
{
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) );
        require_once 'Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();
}

function zff_init()
{
        do_action('load_wp_zff');
}

add_action('plugins_loaded','zff_init');
add_action('load_wp_zff','zff_load');
?>
