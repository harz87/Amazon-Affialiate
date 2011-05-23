<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aa_items
 *
 * @author h_titz
 */
class AA_Items {

    function get($cat_id = null){
        global $wpdb;
	$itemsTable = AA_ITEMS_TABLE;
	$featuresTable = AA_FEATURES_TABLE;
        $picturesTable = AA_PICTURES_TABLE;

        $sqlItems = 'SELECT * FROM ' .$itemsTable;
        if (is_numeric($cat_id)){
            $sqlItems .= ' WHERE category_id='. $cat_id;
        }
        $resultItems = $wpdb->get_results($sqlItems);
        $sqlFeatures = 'SELECT * FROM ' .$featuresTable;
        $resultFeatures = $wpdb->get_results($sqlFeatures);
        $sqlPictures = 'SELECT item_id, picture_filename, picture_title FROM ' .$picturesTable;
        $resultPictures = $wpdb->get_results($sqlPictures);
        foreach ($resultItems as $item) {
            $item->features = array();
            $item->pictures = array();
            foreach ($resultFeatures as $feature) {
                if ($feature->item_id == $item->item_id){
                    $item->features[] = $feature->feature_text;
                }
            }
            foreach ($resultPictures as $picture) {
                if ($picture->item_id == $item->item_id){
                    $item->pictures[$picture->picture_filename] = $picture->picture_title;
                }
            }
            $results[] = $item;

        }
        return $results;

    }



    function output($cat_id = null){
        $items = self::get($cat_id);
        $output = "";
        $item_count = count($items);
       
        if(count($items)>0){
           foreach($items as $item){
            $output .= AA_Item::output($item, get_option('dbaa_item_list_item_format'));
            }
        }
        elseif (count($items)==0 && is_numeric ($cat_id) && $cat_id != null) {
        /**
         * @todo generated Option für keine Produkte in Kategorie
         */
            $output = 'Keine Produkte in dieser Kategorie vorhanden';

        }
        else {
            $output = get_option('dbaa_no_items_message');
        }
        
        return $output;
    }
}
?>
