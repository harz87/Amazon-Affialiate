<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function aa_uninstall(){
    global $wpdb;
    $page_id = get_option('dbaa_created_page');
    $path = get_option('dbaa_images_path');
    $wpdb->query('DELETE FROM ' . $wpdb->prefix .'posts where ID='.$page_id);
    $dbaa_options = array(
        'dbaa_items_page_title' => 'Items',
        'dbaa_items_page_time_limit' => 0,
        'dbaa_item_list_item_format' => '<h3>#_ITEMLINK</h3><br/>#_PICTURELINK #_DESCRIBITION<br/>#_PRICE',
        'dbaa_default_item_list_item_format' => '<h3>#_ITEMLINK</h3><br/>#_PICTURELINK #_DESCRIBITION<br/>#_PRICE',
        'dbaa_single_item_format' => '<h3>#_NAME</h3><br/>#_MAINPICTURE #_DESCRIBITION<br/>#_FEATURELIST<br/>#_ALTERNATIVEPICTURE<br/>#_PRICE<br/>#_AMAZONURL',
        'dbaa_default_single_item_format' => '<h3>#_NAME</h3><br/>#_MAINPICTURE #_DESCRIBITION<br/>#_FEATURELIST<br/>#_ALTERNATIVEPICTURE<br/>#_PRICE<br/>#_AMAZONURL',
        'dbaa_item_page_title_format' => '#_NAME',
        'dbaa_item_page_title_tag_format' => '#_NAME #_EAN',
        'dbaa_item_page_meta_desc_format' => '#_NAME #_NAME #_EAN günstig im online Shop einkaufen. #_NAME Preisvergleich',
	'dbaa_no_items_message' => 'Keine Produkte in die Datenbank importiert',
        'dbaa_categories_list_format' => '#_CATLINK',
        'dbaa_categories_list_title' => 'Amazonkategorien',
        'dbaa_single_category_format' => '#_CATITEMS',
        'dbaa_single_category_title_format' => '#_GERMANNAME',
        'dbaa_images_path' => '/amazonaffl/images/',
        'dbaa_amazon_appid' =>'',
        'dbaa_amazon_secretkey' =>'',
        'dbaa_amazon_associatetag'=>'',
        'dbaa_amazon_coutnrycode'=>'DE',
        'dbaa_items_page' => '',
        'dbaa_created_page' => ''
    );
    foreach ($dbaa_options as $key => $value) {
        delete_option($key);
    }
    deleteDownloads($path);
    $sql = 'DROP TABLE '.AA_CATEGORY_TABLE. ', '.AA_FEATURES_TABLE.', '.AA_PICTURES_TABLE. ', ' . AA_ITEMS_TABLE;
    $wpdb->query($sql);
}

function deleteDownloads($path){
    $mydir = WP_PLUGIN_DIR.'/amazonaffl/images/';
    foreach (scandir($mydir) as $item) {
        if ($item == '.' || $item == '..') continue;
        unlink($mydir.DIRECTORY_SEPARATOR.$item);
    }

}
?>
