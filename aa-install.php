<?php

/**
 *
 * @global <type> $wp_rewrite
 * saves rewrite rules set classes/permalinks.php
 *
 */
function aa_install(){
        global $wp_rewrite;
   	$wp_rewrite->flush_rules();

        aa_create_categories_table();
        
        aa_create_features_table();
        aa_create_pictures_table();
        aa_create_items_table();
        aa_add_options();
        aa_create_items_page();
        aa_add_categories();
}

/**
 * adding different default options to wp_options for later usage.
 * Can be redefined in Admin panel.
 */
function aa_add_options() {
    $dbaa_options = array(
        'dbaa_items_page_title' => 'Items',
        'dbaa_items_page_time_limit' => 0,
        'dbaa_item_list_item_format' => '<h3>#_ITEMLINK</h3><br/>#_PICTURELINK #_DESCRIBITION<br/>#_PRICE',
        'dbaa_default_item_list_item_format' => '<h3>#_ITEMLINK</h3><br/>#_PICTURELINK #_DESCRIBITION<br/>#_PRICE',
        'dbaa_single_item_format' => '#_MAINPICTURE #_DESCRIBITION<br/>#_FEATURELIST<br/>#_ALTERNATIVEPICTURE<br/>#_PRICE<br/>#_AMAZONURL',
        'dbaa_default_single_item_format' => '#_MAINPICTURE #_DESCRIBITION<br/>#_FEATURELIST<br/>#_ALTERNATIVEPICTURE<br/>#_PRICE<br/>#_AMAZONURL',
        'dbaa_item_page_title_format' => '#_NAME',
        'dbaa_item_page_title_tag_format' => '#_NAME #_EAN',
        'dbaa_item_page_meta_desc_format' => '#_NAME #_EAN günstig im online Shop einkaufen. #_NAME Preisvergleich',
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

    	foreach($dbaa_options as $key => $value){
		add_option($key, $value);
	}
}

/**
 *
 * @global <type> $wpdb
 * @global <type> $current_user
 * generating a post where the imported items will be displayed
 */
function aa_create_items_page(){
	global $wpdb;
	if( get_option('dbaa_items_page') == '' ){
		$post_data = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'ping_status' => get_option('default_ping_status'),
			'post_content' => 'CONTENTS',
			'post_excerpt' => 'CONTENTS',
			'post_title' => __('Items','dbaa')
		);
		$post_id = wp_insert_post($post_data, false);
	   	if( $post_id > 0 ){
	   		update_option('dbaa_items_page', $post_id);
                        update_option('dbaa_created_page', $post_id);
	   	}
	}
}

/**
 *
 * @global  $wpdb
 * generating categories table
 */
function aa_create_categories_table(){
    global $wpdb;
    $table = AA_CATEGORY_TABLE;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
    $sql = 'CREATE TABLE ' . $table . '(
        category_id INT AUTO_INCREMENT NOT NULL,
        category_germanname VARCHAR(255) NOT NULL,
        category_englishname VARCHAR(255) NOT NULL,
        category_slug VARCHAR(255) NOT NULL,
        PRIMARY KEY(category_id)) DEFAULT CHARSET=utf8';
    $wpdb->query($sql);
}
/**
 *
 * @global  $wpdb
 * generating Features Table
 */
function aa_create_features_table(){
    global $wpdb;
    $table = AA_FEATURES_TABLE;
    $sql = 'CREATE TABLE '. $table .' (
            feature_id INT AUTO_INCREMENT NOT NULL,
            item_id INT DEFAULT NULL,
            feature_text VARCHAR(255) NOT NULL,
            INDEX features_item_id_idx (item_id),
            PRIMARY KEY(feature_id)) DEFAULT CHARSET=utf8;';
    $wpdb->query($sql);
    
}
/**
 *
 * @global  $wpdb
 * generating items table
 */
function aa_create_items_table(){
    global $wpdb;
    $table = AA_ITEMS_TABLE;
    $sql = 'CREATE TABLE '. $table .' (
        item_id INT AUTO_INCREMENT NOT NULL,
        category_id INT DEFAULT NULL,
        item_title VARCHAR(255) NOT NULL,
        item_slug VARCHAR(255) NOT NULL,
        item_describition LONGTEXT DEFAULT NULL,
        item_price VARCHAR(255) DEFAULT NULL,
        item_url VARCHAR(255) NOT NULL,
        item_asin VARCHAR(255) DEFAULT NULL,
        item_binding VARCHAR(255) DEFAULT NULL,
        item_ean VARCHAR(255) DEFAULT NULL,
        item_label VARCHAR(255) DEFAULT NULL,
        item_manufacturer VARCHAR(255) DEFAULT NULL,
        item_mpn VARCHAR(255) DEFAULT NULL,
        item_publisher VARCHAR(255) DEFAULT NULL,
        item_studio VARCHAR(255) DEFAULT NULL,
        item_author VARCHAR(255) DEFAULT NULL,
        INDEX items_category_id_idx (category_id),
        UNIQUE INDEX item_slug (item_slug),
        PRIMARY KEY(item_id)) DEFAULT CHARSET=utf8';
    $wpdb->query($sql);
}


/**
 *
 * @global  $wpdb
 * generating pictures table
 */
function aa_create_pictures_table(){
    global $wpdb;
    $table = AA_PICTURES_TABLE;
    $sql = 'CREATE TABLE '. $table .' (
        picture_id INT AUTO_INCREMENT NOT NULL,
        item_id INT DEFAULT NULL,
        picture_filename VARCHAR(255) NOT NULL,
        picture_title VARCHAR(255) NOT NULL,
        INDEX pictures_item_id_idx (item_id),
        PRIMARY KEY(picture_id)) DEFAULT CHARSET=utf8';
    $wpdb->query($sql);
}

/**
 *
 * @global  $wpdb
 * adding all default Categories (defined in Zend_clicks_Service_Amazon_Constans to Categories Table
 */
function aa_add_categories(){
    global $wpdb;
    $table = AA_CATEGORY_TABLE;
    require_once(WP_PLUGIN_DIR.'/amazonaffl/Zend/clicks/Service/Amazon/Constans.php');
    foreach(Zend_clicks_Service_Amazon_Constans::$PRODUCTGROUPSDE as $english => $german){
        $data = array('category_germanname'=>$german,
            'category_englishname'=>$english,
            'category_slug' => trim(preg_replace('![^a-z0-9]+!', '-', strtolower($english)),'-'));
        $wpdb->insert($table,$data,'%s');
    }
}

?>
