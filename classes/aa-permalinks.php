<?php

if( !class_exists('AA_Permalinks') ){
	class AA_Permalinks {
		
		static $aa_queryvars = array(
			'item_id', 'item_slug',
                    'category_id', 'category_slug', 'item_categories'
		);
		
		function init(){	
			add_filter('pre_update_option_dbaa_items_page', array('AA_Permalinks','option_update'));
			add_filter('init', array('AA_Permalinks','flush'));
			add_filter('rewrite_rules_array',array('AA_Permalinks','rewrite_rules_array'));
			add_filter('query_vars',array('AA_Permalinks','query_vars'));
			add_action('template_redirect',array('AA_Permalinks','init_objects'), 1);
			add_action('template_redirect',array('AA_Permalinks','redirection'), 1);
			//Add filters to rewrite the URLs
			add_filter('aa_item_output_placeholder',array('AA_Permalinks','rewrite_urls'),1,3);
                        add_filter('aa_category_output_placeholder',array('AA_Permalinks','rewrite_urls'),1,3);

		}
		
		function flush(){
			global $wp_rewrite;
			if( get_option('dbaa_flush_needed') ){
			   	$wp_rewrite->flush_rules();
				delete_option('dbaa_flush_needed');
			}
		}
		
		function rewrite_urls($replace, $object, $result){
			global $wp_query, $wp_rewrite;
                        if( $wp_rewrite->using_permalinks() ){
				switch( $result ){
                                  case '#_ITEMLINK': //HTML Link
					$AA_URI = get_permalink(get_option("dbaa_items_page"));
					$item_link = $AA_URI . '/item/'.$object->item_slug;
                                         $replace = "<a href='{$item_link}' title='{$object->item_title}'>{$object->item_title}</a>";
                                         break;
                                  case '#_PICTURELINK':
                                      $AA_URI = get_permalink(get_option("dbaa_items_page"));
					$item_link = $AA_URI . '/item/'.$object->item_slug;
                                        foreach ($object->pictures as $key => $value) {
                                        $replace = "<a href='{$item_link}'><img src='".WP_PLUGIN_URL. get_option('dbaa_images_path') . $key . "' title='$value'/></a>";
                                        break;
                                        }
                                         break;
                                 case '#_CATLINK':
                                     $AA_URI = get_permalink(get_option("dbaa_items_page"));
					$cat_link = $AA_URI . '/category/'.$object->category_slug;
                                        $replace = "<a href='{$cat_link}' title='{$object->category_germanname}'>{$object->category_germanname}</a>";
                                        break;
				}
			}
			return $replace;
		}
		
		/**
		 * will redirect old links to new link structures.
		 * @return mixed
		 */
		function redirection(){
			global $wp_rewrite, $post, $wp_query;
			if( $wp_rewrite->using_permalinks() && !is_admin() ){
				//is this a querystring url?
				$items_page_id = get_option ( 'dbaa_items_page' );
                                
				if ( is_object($post) && $post->ID == $items_page_id && $items_page_id != 0 ) {
                                    	$page = ( !empty($_GET['page']) && is_numeric($_GET['page']) )? $_GET['page'] : '';
                                        if ( !empty($_GET['item_id']) && is_numeric($_GET['item_id']) ) {
						//single item page
						$item = AA_Item::getSingleItemByID($_GET['item_id']);
						wp_redirect( self::url('item', $item->item_slug), 301);
						exit();
					}			
				}
			}
		}		
		// Adding a new rule
		function rewrite_rules_array($rules){
			//get the slug of the event page
			$items_page_id = get_option ( 'dbaa_items_page' );
			$items_page = get_post($items_page_id);
			$aa_rules = array();
			if( is_object($items_page) ){
				$items_slug = $items_page->post_name;
				$aa_rules[$items_slug.'/item/(\d*)$'] = 'index.php?pagename='.$items_slug.'&item_id=$matches[1]'; //single item page with id
				$aa_rules[$items_slug.'/item/(.+)$'] = 'index.php?pagename='.$items_slug.'&item_slug=$matches[1]'; //single item page with slug
				$aa_rules[$items_slug.'/(\d+)$'] = 'index.php?pagename='.$items_slug.'&page=$matches[1]'; //item pageno
                                $aa_rules[$items_slug.'/categories$'] = 'index.php?pagename='.$items_slug.'&item_categories=1'; //category list with slug
                                $aa_rules[$items_slug.'/category/(\d*)$'] = 'index.php?pagename='.$items_slug.'&category_id=$matches[1]'; //single category page with id
				$aa_rules[$items_slug.'/category/(.+)$'] = 'index.php?pagename='.$items_slug.'&category_slug=$matches[1]'; //category page with slug
			}
                        
			return $aa_rules + $rules;
		}
		

		function url(){
			global $wp_rewrite;
			$args = func_get_args();
			$aa_uri = get_permalink(get_option("dbaa_items_page"));
			if ( $wp_rewrite->using_permalinks() ) {
				$item_link = trailingslashit(trailingslashit($aa_uri). implode('/',$args));
			}
			return $item_link;
		}
		
		/**
		 * checks if the events page has changed, and sets a flag to flush wp_rewrite.
		 * @param mixed $val
		 * @return mixed
		 */
		function option_update( $val ){
			if( get_option('dbaa_items_page') != $val ){
				update_option('dbaa_flush_needed',1);
			}
		   	return $val;
		}
		
		// Adding the id var so that WP recognizes it
		function query_vars($vars){
			foreach(self::$aa_queryvars as $aa_queryvar){
				array_push($vars, $aa_queryvar);
			}
		    return $vars;
		}
		
		/**
		 * Not the "WP way" but for now this'll do! 
		 */
		function init_objects(){
			//Build permalinks here
			global $wp_query, $wp_rewrite;
			if ( $wp_rewrite->using_permalinks() ) {
				foreach(self::$aa_queryvars as $aa_queryvar){
					if( $wp_query->get($aa_queryvar) ) {
						$_REQUEST[$aa_queryvar] = $wp_query->get($aa_queryvar);
					}
				}
		    }
		}
	}
	AA_Permalinks::init();
}

