<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function aa_options_save(){
	if( current_user_can('activate_plugins')&&!empty($_POST['aa-submitted']) ){
            $post = $_POST;
            foreach ($_POST as $postKey => $postValue){
			if( substr($postKey, 0, 5) == 'dbaa_' ){
                            update_option($postKey, stripslashes($postValue));
                        }

        }
        function aa_options_saved_notice(){
			?>
			<div class="updated"><p><strong><?php echo 'Änderungen wurden übernommen'; ?></strong></p></div>

			<?php
		}
		add_action ( 'admin_notices', 'aa_options_saved_notice' );
    }
}
add_action('admin_head', 'aa_options_save');

function aa_admin_options_page() {
    $items_placeholders = '<a href="admin.php?page=aa_sub_help">definierten Platzhalter</a>';
    $items_placeholder_tip = " ". sprintf('Es akzeptiert diese %s.', $items_placeholders);
?>

                    	
                     <form id="dbaa_options_form" method="post" action="">
                    <table class="form-table">
                     <?php
                        $get_pages = get_pages();
                        $page_options = array();
                        $page_options[1] = 'Startseite';
		 	foreach($get_pages as $page){
                            $page_options[$page->ID] = $page->post_title;
			}
			aa_options_select ( 'Darstellungsseite', 'dbaa_items_page', $page_options, 'Setzt die Seite, wo die Produkte angezeigt werden' );
			aa_options_textarea ('Standard Produkt Format in Liste', 'dbaa_item_list_item_format', 'Das Format eines Produktes in einer Liste.'.$items_placeholder_tip, 'dbaa_default_item_list_item_format' );
			aa_options_input_text ('Standard Produktdetailseite Seitenüberschrift Format ', 'dbaa_item_page_title_format','Das Format der Seitenüberschrift einer Produktdetailseite.'.$items_placeholder_tip );
			aa_options_textarea ('Standard Produktdetailseiten Format', 'dbaa_single_item_format','Das Format einer Produktdetailseite.'. $items_placeholder_tip, 'dbaa_default_single_item_format');
                        aa_options_input_text ( 'Standard Produktdetailseiten Title Tag', 'dbaa_item_page_title_tag_format',$items_placeholder_tip, 'Standard Title Tag für eine Produktedetailseite.'.$items_placeholder_tip);
                        aa_options_input_text ( 'Standard Produktdetailseiten Meta Describiton', 'dbaa_item_page_meta_desc_format',$items_placeholder_tip);
			aa_options_input_text ('Produktlisten Title','dbaa_items_page_title','Titel der Produktlisten Seite.');
			aa_options_input_text ( 'Kein Produkt Nachricht', 'dbaa_no_items_message','Nachricht die angezeigt wird, wenn keine Produkte in DB vorhanden.' );
                        aa_options_textarea ('Format der Kategorien in einer Liste', 'dbaa_categories_list_format', 'Das Format eines Kategorieelement in Liste'.$items_placeholder_tip );
			aa_options_input_text ('Standard Kategorienseiten Title Format ', 'dbaa_single_category_title_format','Das Format des Titels einer Kategorienseite.'.$items_placeholder_tip );
			aa_options_textarea ('Standard Kategorieseiten Format', 'dbaa_single_category_format','Das Format einer einzelnen Kategorieseite.'.$items_placeholder_tip );
			aa_options_input_text ('Kategorienlisten Title','dbaa_categories_list_title','Titel der Kategorienliste.');
                        aa_options_input_text ( 'Amazon ApiKey', 'dbaa_amazon_appid','' );
                        aa_options_input_text ( 'Amazon SecretKEy', 'dbaa_amazon_secretkey','' );
                        aa_options_input_text ( 'Amazon PartnerID (Associatetag)', 'dbaa_amazon_associatetag','Die PartnerID von Amazon bereitgestellt' );
                        aa_options_input_text ( 'Amazon Country Code', 'dbaa_amazon_coutnrycode','Mögliche Werte (DE,US,UK,JP,FR,CA) ' );

                       ?>
                        </table>

                        <p class="submit">
			<input type="submit" id="dbaa_options_submit" name="Submit" value="speichern"/>
                        <input type="hidden" name="aa-submitted" value="1" />
			</p>
                        </form>

<?php
}
?>
