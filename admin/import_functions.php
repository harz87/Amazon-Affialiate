<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function formatResult($result){
    $formAction = 'admin.php?page=aa_import_item&action=save';
    $filename = $result->Title;
    $umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
    $replace = Array("ae","oe","ue","Ae","Oe","Ue","ss");

    $filename = preg_replace($umlaute, $replace, $filename);
    $filename = trim(preg_replace('![^a-z0-9]+!', '-', strtolower($filename)),'-');
    ?>

    <form name="<?php echo 'item'?>" id="<?php echo 'item'?>" method="post" action="<?php echo $formAction; ?>">
        <table class="form-table">
       <?php
           aa_import_input_text('Produktname', 'title', $result->Title);
           aa_import_main_photo('featurephoto', $result->Title, $filename, $result->MediumImage->Url);
           for ($i = 0; $i < count($result->images['MediumImage']); $i++){
               aa_import_variant_photo('variantphoto', $result->Title, $result->images['MediumImage'][$i]->Url, $i);
           }
           aa_import_textarea('Produktbeschreibung', 'desc', '');
           for ($i = 0;$i < count($result->Feature); $i++) {
               $j = $i + 1;
               $title = 'Feature '.$j;
               aa_import_input_text($title, 'feature'.$i , $result->Feature[$i]);
           }
           $vk = '';
            if(isset ($result->Offers->Offers)) {
                $vk = $result->Offers->Offers[0]->FormattedPrice;
            }
            elseif(isset ($result->FormattedPrice)) {
                $vk = $result->FormattedPrice;
            }
           aa_import_input_text('Verkaufspreis Amazon:', 'price', $vk, TRUE);
           aa_import_input_text('Produkt URL:', 'url', $result->DetailPageURL, TRUE);
           aa_import_hidden_input('asin', $result->ASIN);
           aa_import_hidden_input('binding', $result->Binding);
           aa_import_hidden_input('ean', $result->EAN);
           aa_import_hidden_input('label', $result->Label);
           aa_import_hidden_input('manufacturer', $result->Manufacturer);
           aa_import_hidden_input('mpn', $result->MPN);
           aa_import_hidden_input('productGroup', $result->ProductGroup);
           aa_import_hidden_input('publisher', $result->Publisher);
           aa_import_hidden_input('studio', $result->Studio);
           aa_import_hidden_input('author', $result->Author);
           aa_import_hidden_input('countVariant', count($result->images['MediumImage']));
           aa_import_hidden_input('countFeature', count($result->Feature));
       ?>
         </table>

                <input type="submit" name="save" class="item" value="in DB übernehmen"/>
   </form>


<?php
}

function saveItem($post){
    global $wpdb;
    $itemTable = AA_ITEMS_TABLE;
    $slug = strtolower($post['title']);
    $umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
    $replace = Array("ae","oe","ue","Ae","Oe","Ue","ss");
   
    $slug = preg_replace($umlaute, $replace, $slug);
     
    $slug = trim(preg_replace('![^a-z0-9]+!', '-', strtolower($slug)),'-');
    $item = array(
           'category_id' => aa_Categories::getCatId($post['productGroup']),
           'item_title' => $post['title'],
           'item_slug' => $slug,
           'item_describition' => $post['desc'],
           'item_price' => $post['price'],
           'item_url' => $post['url'],
           'item_asin' => $post['asin'],
           'item_binding' => $post['binding'],
           'item_ean' => $post['ean'],
           'item_label' => $post['label'],
           'item_manufacturer' => $post['manufacturer'],
           'item_mpn' => $post['mpn'],
           'item_publisher' => $post['publisher'],
           'item_studio' => $post['studio'],
           'item_author' => $post['author'],
        );
    $itemFormat = array('%d', '%s', '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s');
    $wpdb->insert($itemTable, $item, $itemFormat);
    $itemId = $wpdb->insert_id;
    for($i = 0; $i<$post['countFeature']; $i++){
        saveFeature($post['feature'.$i],$itemId);
    }
    //saveFeatures($post['features'], $itemId);
    $pic = new AA_Picture($post['pictureTitleTag'], $post['pictureFilename'], $post['pictureUrl'], $itemId);
    $pic->download();
    for ($i = 0; $i < $post['countVariant']; $i++) {
            if (isset($post['pictureCheck'.$i])) {
                $pic = new AA_Picture($post['pictureTitleTag'.$i], $post['pictureFilename'].'-'.$i, $post['pictureUrl'.$i], $itemId);
                $pic->download();
            }
    }
    if($itemId != 0){
        return true;
    }
    else {
        return false;
    }
    
}



function saveFeature($feature, $itemId){
    global $wpdb;
    $table = AA_FEATURES_TABLE;
    if($feature != '' && $feature != null){
        $wpdb->insert($table, array('feature_id'=>null,'item_id'=>$itemId,'feature_text'=>$feature), array('%d', '%d', '%s'));
    }

}

function searchItem($keyword,$asin,$selectedSearchIndex,$itempage){
    $searchIndex = Zend_clicks_Service_Amazon_Constans::$SEARCHINDEXDE;
    $formAction = 'admin.php?page=aa_import_item&action=search';
    ?>
<form name="amazonsearch" method="post" action="<?php echo $formAction; ?>">
    <input type="hidden" class="search" name="itempage" value="<?php echo $itempage; ?>"/>
    <label>Suchbegriff</label>
    <input type="text" class="search" name="keyword" value="<?php echo $keyword; ?>"/>
    <label>Kategorie</label>
    <select name="searchIndex">
       <?php foreach($searchIndex as $index => $value) :
            $selected = $selectedSearchIndex == $index ? ' selected="selected"' : '';
        ?>
        <option value="<?php echo $index ?>"<?php echo $selected; ?>><?php echo $value ?></option>
	<?php endforeach; ?>
    </select>
    <label>ASIN</label>
    <input type="text" class="search" name="asin" value="<?php echo $asin; ?>"/>
    <input type="submit" name="search" value="suchen"/>
</form>
<?php if($itempage > 1 ){
    $formAction = 'admin.php?page=aa_import_item&action=back';
    ?>
<form name="amazonsearch" method="post" action="<?php echo $formAction; ?>">
    <input type="submit" name="back" value="zurück"/>
    <input type="hidden" class="search" name="itempage" value="<?php echo $itempage-1; ?>"/>
    <input type="hidden" class="search" name="keyword" value="<?php echo $keyword; ?>"/>
    <input type="hidden" class="search" name="searchIndex" value="<?php echo $selectedSearchIndex; ?>"/>
</form>
<?php }
if(($selectedSearchIndex == 'All' && $itempage >= 1 && $itempage < 5)||($selectedSearchIndex != 'All' && $itempage >= 1)){
    $formAction = 'admin.php?page=aa_import_item&action=next';
    ?>
<form name="amazonsearch" method="post" action="<?php echo $formAction; ?>">
    <input type="submit" name="next" value="weiter"/>
    <input type="hidden" class="search" name="itempage" value="<?php echo $itempage+1; ?>"/>
    <input type="hidden" class="search" name="keyword" value="<?php echo $keyword; ?>"/>
    <input type="hidden" class="search" name="searchIndex" value="<?php echo $selectedSearchIndex; ?>"/>
</form>
<?php }
}
?>
