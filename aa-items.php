<?php

/**
 *
 * @global <type> $wp_query
 * @global <type> $item
 * @global <type> $aa_category
 * @global <type> $wpdb
 * @param <type> $page_content
 * @return string
 * overrinding existing Content on page with requested page content
 */
function aa_content($page_content) {
    $items_page_id = get_option('dbaa_items_page');
    global $wp_query, $item, $aa_category;

    if(get_the_ID ()==$items_page_id && $items_page_id != 0){
        global $wpdb;
        $content = apply_filters('aa_content_pre', '', $page_content);
        if( empty($content) ){
            ob_start();
            if (is_object($item) && !empty ($item->item_title)) {
            // single item page
                echo AA_Item::output_single($item->item_id, get_option('dbaa_single_item_format'));
            }
            elseif(is_object($aa_category) && !empty($aa_category->category_englishname)){
                echo aa_Categories::outputSingle($aa_category);
            }
            elseif(!empty ($_REQUEST['item_categories'])){
                echo aa_Categories::outputAll();
            }
            else{
                echo AA_Items::output();
            }
            $content = ob_get_clean();
        }
        return '<div id="aa-wrapper">'.$content.'</div>';
    }
    return $page_content;
}
add_filter('the_content', 'aa_content');

/**
 *
 * @global  $wp_query
 * @global <type> $post
 * @param <type> $data
 * @return string
 */
function aa_the_title($data){
    global $wp_query, $post, $item, $aa_category;
    if (get_option('dbaa_items_page') == $post->ID){
         if ( $wp_query->in_the_loop ) {
               if (is_object($item) && !empty ($item->item_title)) {
			// single event page
			$content = AA_Item::output_single($item->item_id, get_option('dbaa_item_page_title_format') );
                }
                elseif(is_object($aa_category) && !empty($aa_category->category_englishname)){
                    $content = aa_Categories::formatCategory(get_option('dbaa_single_category_title_format'),$aa_category);
                }
                elseif(!empty ($_REQUEST['item_categories'])){
                    $content = get_option('dbaa_categories_list_title');
                }
                else{
			$content =  get_option ( 'dbaa_items_page_title' );

		}
                return $content;
	}
        
    }
    return $data;
}
add_filter ('the_title', 'aa_the_title' );



function aa_items_page_title($content){
	global $post, $item, $aa_category;
	$page_id = get_option ( 'dbaa_items_page' );
	if ( $post->ID == $page_id && $page_id != 0 ){
                if (is_object($item) && !empty ($item->item_title)) {
			// single event page
			$content = AA_Item::output_single($item->item_id, get_option('dbaa_item_page_title_tag_format') );
                }
                elseif(is_object($aa_category) && !empty($aa_category->category_englishname)){
                    $content = aa_Categories::formatCategory(get_option('dbaa_single_category_title_format'),$aa_category);
                }
                elseif(!empty ($_REQUEST['item_categories'])){
                    $content = get_option('dbaa_categories_list_title');
                }
                else{
			$content =  get_option ( 'dbaa_items_page_title' );

		}
                
                return $content . ' | ';
	}
        
        return $content;
}
add_filter ( 'wp_title', 'aa_items_page_title',10,1 );

function aa_seometa_add_meta(){
        global $post, $item, $aa_category;
	$page_id = get_option ( 'dbaa_items_page' );
        if ( $post->ID == $page_id && $page_id != 0 ){
        if (is_object($item) && !empty ($item->item_title)) {
            $content = AA_Item::output_single($item->item_id, get_option('dbaa_item_page_meta_desc_format') );
        }
        elseif(is_object($aa_category) && !empty($aa_category->category_englishname)){
            $content = 'Produkte zur Kategorie ' . $aa_category->category_germanname;
        }
        elseif(!empty ($_REQUEST['item_categories'])){
            $content = get_option('dbaa_categories_list_title');
        }
        else {
            $content = get_option ( 'dbaa_items_page_title' );
        }

        ?>
        <meta name="description" content="<?php echo $content?>"/>
    <?php
    }
}
add_action('wp_head', 'aa_seometa_add_meta',99);


?>
