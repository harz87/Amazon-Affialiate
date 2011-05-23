<?php
/*
Plugin Name: Amazon Affiliate
Version: 0.1
Plugin URI: http://clicks.de
Description: Displays imported amazon Data in page
Author: Hardy Titz
Author URI: http://clicks.de
*/
// Setting constants
define('AA_VERSION', 0.1); //self expanatory
define('AA_ITEMS_TABLE', 'items'); //TABLE NAME
define('AA_PICTURES_TABLE','pictures'); //TABLE NAME
define('AA_FEATURES_TABLE','features'); //TABLE NAME
define('AA_CATEGORY_TABLE','categories');
include_once("aa-items.php");
include_once ("aa-functions.php");
include_once("setupZend.php");

include_once("classes/aa_items.php");
include_once("classes/aa_item.php");
include_once ("classes/aa_picture.php");
include_once('classes/aa-permalinks.php');
include_once("classes/aa_categories.php");

include_once("admin/aa_items.php");
include_once("admin/aa_item.php");
include_once("admin/aa_import.php");
include_once("admin/aa_categories.php");
include_once("admin/aa_options.php");
include_once("admin/aa_help.php");

include_once("widgets/aa_Categories.php");

/**
 *
 * @global <type> $item
 * @global <type> $aa_category
 * Checks if a single Item or Category is called and saves it in a global variable
 */
function aa_load_item(){
	define('AA_URI', get_permalink(get_option("dbaa_items_page"))); //PAGE URI OF AA
        global $item, $aa_category;
        
        if ( !empty($_REQUEST['item_id']) && is_numeric($_REQUEST['item_id']) ) {
            // single Item page
            $item = AA_Item::getSingleItemByID($_REQUEST['item_id']);
        }
        elseif( isset($_REQUEST['item_slug'])){
            $item = AA_Item::getSingleItemBySlug( $_REQUEST['item_slug']);
        }
        elseif(!empty($_REQUEST['category_id']) && is_numeric($_REQUEST['category_id'])){
            $aa_category = aa_Categories::getCategoryByID($_REQUEST['category_id']);
        }
        elseif(isset($_REQUEST['category_slug'])){
            $aa_category = aa_Categories::getCategoryBySlug( $_REQUEST['category_slug']);
            
        }


}
add_action('template_redirect', 'aa_load_item', 1);
if (is_admin()){add_action('init', 'aa_load_item', 1);}

/**
 * Function is called on Activation and sets the cronjob and calls aa_install in aa-install.php
 */
function aa_activate() {
	require_once(WP_PLUGIN_DIR.'/amazonaffl/aa-install.php');
        wp_schedule_event(time(), 'hourly', 'aa_hourly_event');
	aa_install();
}
register_activation_hook( __FILE__,'aa_activate');
add_action('aa_hourly_event', 'updateItemsDataHourly');

function aa_deactivate() {
    require_once(WP_PLUGIN_DIR.'/amazonaffl/aa-uninstall.php');
    wp_clear_scheduled_hook('aa_hourly_event');
    aa_uninstall();
}
register_deactivation_hook(__FILE__,'aa_deactivate');
/**
 * generating main Admin menu
 */
function aa_plugin_menu() {
	//create Amazon Affialiate menu
    add_object_page('Amazon Affialiate','Amazon Affialiate','edit_items','aa_main_menu','aa_admin_items_page');
	//add_menu_page('Amazon Affialiate', 'Amazon Affialiate', 'administrator', 'aa_main_menu','aa_admin_items_page');
        //create submenu
        $plugin_pages = array();
        $plugin_pages[]=add_submenu_page('aa_main_menu', 'Produkte', 'Produkte', 'administrator','aa_edit_item', 'aa_admin_item_page');
        $plugin_pages[]=add_submenu_page('aa_main_menu', 'Produktimport', 'Produktimport', 'administrator','aa_import_item', 'aa_admin_import_page');
        $plugin_pages[]=add_submenu_page('aa_main_menu', 'Kategorien', 'Kategorien', 'administrator','aa-cat', 'aa_admin_categories_page');
        $plugin_pages[]=add_submenu_page('aa_main_menu', 'Einstellungen', 'Einstellungen', 'administrator','aa_sub_settings', 'aa_admin_options_page');
	$plugin_pages[]=add_submenu_page('aa_main_menu', 'Hilfe', 'Hilfe', 'administrator', 'aa_sub_help', 'aa_admin_help_page');
	foreach($plugin_pages as $plugin_page){
				add_action( 'admin_print_styles-'. $plugin_page, 'aa_admin_load_styles' );
			}
}
add_action('admin_menu', 'aa_plugin_menu');

function aa_admin_load_styles() {
	wp_enqueue_style('amazon-affl-admin', WP_PLUGIN_URL.'/amazonaffl/includes/css/amazon_affl_admin.css');
}



?>
