<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aa_categories
 *
 * @author h_titz
 */
class aa_Categories {

    static $formatAll = '#_CATLINK';
    static $formatSingle = '#_GERMANNAME<br/>#_CATITEMS';

    function getAllCategories(){
        global $wpdb;
	$table = AA_CATEGORY_TABLE;
        $sql = 'SELECT * FROM ' .$table;
        $results= $wpdb->get_results($sql);
        return $results;
    }

    function getCatId($name){
        global $wpdb;
        $table = AA_CATEGORY_TABLE;
        $sql = 'SELECT category_id FROM ' . $table . ' WHERE category_englishname="'.$name . '"';
        $result = $wpdb->get_row($sql);
        return intval($result->category_id);
    }
    function getCategoryByID($category_id){
        global $wpdb;
        $table = AA_CATEGORY_TABLE;
        $sql = 'SELECT * FROM ' . $table . ' WHERE category_id='.$category_id;
        $result = $wpdb->get_row($sql);
        return $result;
    }

    function getCategoryBySlug($slug){
        global $wpdb;
        $table = AA_CATEGORY_TABLE;
        $sql = 'SELECT * FROM ' . $table . ' WHERE category_slug="'.$slug .'"';
        $result = $wpdb->get_row($sql);
        return $result;
    }
    
    function updateCategory($category) {
        global $wpdb;
        $table = AA_CATEGORY_TABLE;
        return $wpdb->update($table, array('category_germanname'=>$category->category_germanname),array('category_id'=>$category->category_id),'%s','%d');

    }

    function getCategoriesWithProducts(){
        global $wpdb;
        $itemsTable = AA_ITEMS_TABLE;
        $catTable = AA_CATEGORY_TABLE;
        $sql = 'SELECT c . * , count( i.item_id ) AS Anzahl FROM items i, categories c WHERE i.category_id = c.category_id GROUP BY c.category_id HAVING count( i.item_id ) >0';       $result = $wpdb->get_row($sql);
        $results= $wpdb->get_results($sql);
        return $results;
    }

    function outputAll() {
        $categories = self::getAllCategories();
        $output= '<ul>';
        foreach ($categories as $category){
            $output .= '<li>'. self::formatCategory(get_option('dbaa_categories_list_format'), $category) .'</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    function outputAllWithProducts(){
        $categories = self::getCategoriesWithProducts();
        $output= '<ul>';
        foreach ($categories as $category){
            $output .= '<li>'. self::formatCategory(get_option('dbaa_categories_list_format'), $category) .'</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    function outputSingle($category)
    {
        return self::formatCategory(get_option('dbaa_single_category_format'), $category);
    }



    function formatCategory($format,$category){
        $output = $format;
        preg_match_all("/#@?_?[A-Za-z0-9]+/", $format, $placeholders);
        foreach($placeholders[0] as $result){
            $replace = '';
            switch( $result ){
                case '#_GERMANNAME':
                    $replace = $category->category_germanname;
                    break;
                case '#_ENGLISHNAME':
                    $replace = $category->category_englishname;
                    break;
                case '#_CATLINK':
                       $AA_URI = AA_URI;
                       $joiner = (stristr($AA_URI, "?")) ? "&amp;" : "?";
                       $cat_link = $AA_URI.$joiner."category_id=".$category->category_id;
                       $replace = "<a href='{$cat_link}' title='{$category->category_germanname}'>$category->category_germanname</a>";
                       break;
                case '#_CATITEMS':
                    $replace = AA_Items::output($category->category_id);
                    break;

            }
            $replace = apply_filters('aa_category_output_placeholder', $replace, $category, $result, $target);
            $output = str_replace($result, $replace , $output );
        }
        return $output;
    }

    function getCategoryOptions(){
       $results = self::getAllCategories();
       $options = array();
       foreach ($results as $category){
           $options[$category->category_id]= $category->category_germanname;
       }
       return $options;
    }

}
?>
