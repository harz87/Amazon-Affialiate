<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aa_item
 *
 * @author h_titz
 */
class AA_Item{

   
        function output($item, $format, $target='html'){
            $output = $format;
            preg_match_all("/#@?_?[A-Za-z0-9]+/", $format, $placeholders);
            foreach($placeholders[0] as $result){
                $replace = '';
                switch( $result ){
                    case '#_NAME':
                        $replace = $item->item_title;
                        break;
                    case '#_DESCRIBITION':
                        $replace = $item->item_describition;
                        break;
                    case '#_PRICE':
                        $replace = preg_replace('/EUR/','€',$item->item_price);
                        break;
                    case '#_AMAZONURL':
                       $replace = "<a href='{$item->item_url}' title='{$item->item_title}'>Zum Shop</a>";
                       break;
                   case '#_ASIN':
                       $replace = $item->item_asin;
                       break;
                   case '#_EAN':
                       $replace = $item->item_ean;
                       break;
                   case '#_LABEL':
                       $replace = $item->item_label;
                       break;
                   case '#_MANUFACTURER':
                       $replace = $item->item_manufacturer;
                       break;
                   case '#_PRODUCTGROUP':
                       $replace = $item->item_productGroup;
                       break;
                   case '#_PUBLISHER':
                       $replace = $item->item_publisher;
                       break;
                   case '#_STUDIO':
                       $replace = $item->item_studio;
                       break;
                   case '#_AUTHOR':
                       $replace = $item->item_author;
                       break;
                    case '#_ITEMLINK':
                        $AA_URI = AA_URI;
                       $joiner = (stristr($AA_URI, "?")) ? "&amp;" : "?";
			$item_link = $AA_URI.$joiner."item_id=".$item->item_id;
                        //$item_link = AA_URI . $item->item_post_name;
                        $replace = "<a href='{$item_link}' title='{$item->item_title}'>$item->item_title</a>";
                        break;

                   case '#_FEATURELIST':
                       $replace = "<ul>";
                       foreach ($item->features as $feature){
                           if($feature != ''){
                            $replace .= '<li>'.$feature.'</li>';
                           }
                       }
                       $replace .= "</ul>";
                       break;
                   case '#_MAINPICTURE':
                       foreach ($item->pictures as $key => $value) {
                           $replace = "<img src='".WP_PLUGIN_URL. get_option('dbaa_images_path') . $key . "' title='$value'/>";
                           break;
                       }
                       break;
                   case '#_PICTURELINK':
                       $AA_URI = AA_URI;
                       $joiner = (stristr($AA_URI, "?")) ? "&amp;" : "?";
			$item_link = $AA_URI.$joiner."item_id=".$item->item_id;
                       foreach ($item->pictures as $key => $value) {
                           $replace = "<a href='{$item_link}'><img src='".WP_PLUGIN_URL. get_option('dbaa_images_path') . $key . "' title='$value'/></a>";
                           break;
                       }
                       break;
                   case '#_ALTERNATIVEPICTURE':
                       $first = true;
                       foreach ($item->pictures as $key => $value) {
                           if (!$first){
                             $replace .= "<img src='" .WP_PLUGIN_URL. get_option('dbaa_images_path'). $key . "' title='$value'/>";
                           }
                           else{
                               $first = false;
                               continue;
                           }
                       }
                       break;

                }
                $replace = apply_filters('aa_item_output_placeholder', $replace, $item, $result, $target);
                $output = str_replace($result, $replace , $output );
            }
            return $output;
        }

        function getSingleItemByID($item_id){
            global $wpdb;
            $itemsTable = AA_ITEMS_TABLE;
            $featuresTable = AA_FEATURES_TABLE;
            $picturesTable = AA_PICTURES_TABLE;

            $sqlItem = 'SELECT i.* FROM ' .$itemsTable .' i WHERE i.item_id='.$item_id;
            $item = $wpdb->get_row($sqlItem);
            $sqlFeatures = 'SELECT * FROM ' .$featuresTable . ' WHERE item_id='.$item_id;
            $resultFeatures = $wpdb->get_results($sqlFeatures);
            $sqlPictures = 'SELECT item_id, picture_filename, picture_title FROM ' .$picturesTable . ' WHERE item_id='.$item_id;
            $resultPictures = $wpdb->get_results($sqlPictures);
            $item->features = array();
            $item->pictures = array();
            foreach ($resultFeatures as $feature) {
                if ($feature->item_id == $item->item_id){
                    $item->features[$feature->feature_id] = $feature->feature_text;
                }
            }
            foreach ($resultPictures as $picture) {
                if ($picture->item_id == $item->item_id){
                    $item->pictures[$picture->picture_filename] = $picture->picture_title;
                }
            }
            return $item;
        }

        function getSingleItemBySlug($item_slug){
            global $wpdb;
            $itemsTable = AA_ITEMS_TABLE;
            $featuresTable = AA_FEATURES_TABLE;
            $picturesTable = AA_PICTURES_TABLE;
            $sqlItem = 'SELECT i.* FROM ' .$itemsTable .' i WHERE i.item_slug="'.$item_slug.'"';
            $item = $wpdb->get_row($sqlItem);
            $sqlFeatures = 'SELECT * FROM ' .$featuresTable . ' WHERE item_id='.$item->item_id;
            $resultFeatures = $wpdb->get_results($sqlFeatures);
            $sqlPictures = 'SELECT item_id, picture_filename, picture_title FROM ' .$picturesTable . ' WHERE item_id='.$item->item_id;
            $resultPictures = $wpdb->get_results($sqlPictures);
            $item->features = array();
            $item->pictures = array();
            foreach ($resultFeatures as $feature) {
                 if ($feature->item_id == $item->item_id){
                    $item->features[$feature->feature_id] = $feature->feature_text;
                }
            }
            foreach ($resultPictures as $picture) {
                if ($picture->item_id == $item->item_id){
                    $item->pictures[$picture->picture_filename] = $picture->picture_title;
                }
            }
            return $item;
        }
        function output_single($item_id, $format){
            $item = self::getSingleItemByID($item_id);
            $output = self::output($item, $format);
            return $output;
        }

        function output_single_slug($slug, $format){
            $item = self::getSingleItemBySlug($slug);
            $output = self::output($item, $format);
            return $output;
        }

        function update($item){
            global $wpdb;
            $itemsTable = AA_ITEMS_TABLE;
            $featuresTable = AA_FEATURES_TABLE;
            $data = self::item2Array($item);
            
            $wpdb->update($itemsTable,$data,array('item_id' => $item->item_id),
                    array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'),'%d');
        }

        function item2Array($item) {
          if (is_object($item)) {
                foreach ($item as $key => $value) {
                    if( substr($key, 0, 5) == 'item_' && $key != 'item_id'){
                        $array[$key] = $value;
                    }
                    elseif($key=='category_id'){
                        $array[$key]=$value;
                    }
                    
                }
                 return $array;
            }
        }

        function updatePrice($price, $item_id){
            global $wpdb;
            $itemsTable = AA_ITEMS_TABLE;
            $wpdb->update($itemsTable,array('item_price'=>$price),array('item_id' => $item_id),'%s','%d');
        }

        function addNewFeature($feature, $item_id){
            global $wpdb;
            $table = AA_FEATURES_TABLE;
            if($feature != '' && $feature != null){
                $wpdb->insert($table,array('item_id'=>$item_id, 'feature_text'=>$feature),array('%d','%s'));
            }
        }

        function updateFeature($feature, $feature_id, $item_id){
            global $wpdb;
            $table = AA_FEATURES_TABLE;
            if($feature != '' && $feature != null){
               $wpdb->update($table,array('feature_text'=>$feature),array('item_id'=>$item_id, 'feature_id'=>$feature_id),'%s','%d');
            }
            elseif($feature == '' && $feature == null) {
                 $sql = 'DELETE FROM '. $table . ' WHERE feature_id='.$feature_id;
                  $wpdb->query($sql);
            }

        }
        

}
?>
