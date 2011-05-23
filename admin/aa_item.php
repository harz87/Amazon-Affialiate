<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function aa_item_save(){
	if( !empty($_POST['aa-item-submitted']) ){
            $item = AA_Item::getSingleItemByID($_POST['item_id']);

            $item->item_title = $_POST['item_title'];
            $item->item_slug = trim(preg_replace('![^a-z0-9]+!', '-', strtolower($item->item_title)),'-');
            $item->item_describition = $_POST['item_desc'];
            $item->item_label = $_POST['item_label'];
            $item->item_manufacturer = $_POST['item_manufacturer'];
            $item->item_publisher = $_POST['item_publisher'];
            $item->item_studio = $_POST['item_studio'];
            $item->item_author = $_POST['item_author'];
            $item->item_price = $_POST['item_price'];
            $item->item_asin = $_POST['item_asin'];
            $item->item_ean = $_POST['item_ean'];
            $item->category_id = $_POST['category'];
            $i = 1;

                if(count($item->features)>0){
                    foreach ($item->features as $feature_id => $feature) {
                        AA_Item::updateFeature($_POST['feature_'.$i],$feature_id,$item->item_id);
                        $i++;
                    }
                    if(count($item->features)<$_POST['countFeature']){
                       for ($i;$i<=$_POST['countFeature'];$i++){
                            var_dump('feature');
                            AA_Item::addNewFeature($_POST['feature_'.$i],$item->item_id);
                        }
                    }
                }
                elseif(count($item->features)==0 && $_POST['countFeature']>0) {
                        for ($j=1; $j < $_POST['countFeature']+1; $j++){
                          AA_Item::addNewFeature($_POST['feature_'.$j],$item->item_id);
                        }
                    }




            if(AA_Item::update($item)==0){


        function aa_item_saved_notice(){
			?>
			<div class="updated"><p><strong><?php echo 'Änderungen wurden übernommen'; ?></strong></p></div>

			<?php
		}
		add_action ( 'admin_notices', 'aa_item_saved_notice' );
            }
            else {
                function aa_item_not_saved_notice(){
                    ?>
			<div class="updated"><p><strong><?php echo 'Die Änderungen könnten nicht gespeichert werden'; ?></strong></p></div>

			<?php
                }
                add_action ( 'admin_notices', 'aa_item_not_saved_notice' );
            }
        }
 }

add_action('admin_head', 'aa_item_save');

function aa_admin_item_page(){
    if(empty ($_REQUEST['item_id'])){
        echo aa_admin_items_page();
    }
    elseif($_REQUEST['action']=='item_edit'){
        $item = AA_Item::getSingleItemByID($_REQUEST['item_id']);
        if(is_object($item) && !empty ($item->item_title)){
            ?>
       <script type="text/javascript">
       function addFeatureRow(id) {
           var counter = document.getElementById('countFeature');
           var counterValue =  document.getElementById('countFeature').value;
           counterValue = parseInt(counterValue) + 1;
           counter.value = counterValue;
              var CATTR = document.getElementById('category_row');
              var TABLE = document.getElementById('item_table');
              var BODY=TABLE.getElementsByTagName('tbody')[0];
              var TR = document.createElement('tr');
              var TD1 = document.createElement('th');
              var TD2 = document.createElement('td');
              var TD3 = document.createElement('td');
              id = id +1;
              name = 'feature_'+id;
              TD1.innerHTML = 'Feature ' + id;
              TD2.innerHTML = '<input type="text" style="width:95%" size="45" name='+name+' id="'+name+'" value="" />';
              TD3.innerHTML = '<input type="button" value="+" onclick="addFeatureRow('+id+')"/>';
              TR.appendChild (TD1);
              TR.appendChild (TD2);
              TR.appendChild (TD3);
              BODY.insertBefore(TR,CATTR);

      }
     </script>
            <h2><?php echo $item->item_title; ?></h2>
            <form id="item-form" method="post" action="">
               <table class="form-table" id="item_table">
                    <input type="hidden" name="item_id" value="<?php echo $item->item_id;?>"/>
                    <?php
                    aa_input_text('Produktname:','item_title',$item->item_title);
                    aa_textarea('Beschreibung:', 'item_desc', $item->item_describition);
                    aa_input_text('Label:','item_label',$item->item_label);
                    aa_input_text('Hersteller:','item_manufacturer',$item->item_manufacturer);
                    aa_input_text('Publisher:','item_publisher',$item->item_publisher);
                    aa_input_text('Studio:','item_studio',$item->item_studio);
                    aa_input_text('Author:','item_author',$item->item_author);
                    aa_input_text('Preis:','item_price',$item->item_price,'',TRUE);
                    aa_input_text('ASIN:','item_asin',$item->item_asin,'',TRUE);
                    aa_input_text('EAN:','item_ean',$item->item_ean,'',TRUE);
                    $i=1;
                    if (count($item->features)!= 0){
                        foreach ($item->features as $feature){
                            aa_features_input_text('Feature '.$i.':','feature_'.$i,$feature,count($item->features));
                            $i++;
                        }
                    }
                    else {
                        aa_features_input_text('Feature '.$i.':', 'feature_'.$i, '');
                    }
                    echo '</div>';
                    $categories = aa_Categories::getCategoryOptions();
                    aa_select('Kategorie','category',$categories,$item->category_id);
                    ?>
                    </table>

                    <input type="hidden" id="countFeature" name="countFeature" value="<?php echo count($item->features)?>"/>
                    <input type="submit" id="dbaa_item_submit" name="Submit" value="speichern"/>
                    <input type="hidden" name="aa-item-submitted" value="1" />

            </form>
        <?php
        }
        else{
            echo 'Das von Ihnen ausgewählte Produkt kann nicht gefunden werden.';
        }
       }
        elseif($_REQUEST['action']=='item_delete'){
            global $wpdb;
            $wpdb->query ( "DELETE FROM ". AA_FEATURES_TABLE ." WHERE item_id=".$_REQUEST['item_id'] );
            $wpdb->query ( "DELETE FROM ". AA_PICTURES_TABLE ." WHERE item_id=".$_REQUEST['item_id'] );
            $wpdb->query ( "DELETE FROM ". AA_ITEMS_TABLE ." WHERE item_id=".$_REQUEST['item_id'] );

            echo aa_admin_items_page();
        }



}
?>
