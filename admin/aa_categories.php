<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function aa_admin_categories_page() {
    global $wpdb;
    $categories = aa_Categories::getAllCategories();
    if ($_REQUEST['action']=='edit' && !empty ($_REQUEST['category_id'])) {
        aa_admin_category_edit();
    }
    elseif ($_REQUEST['action']=='save' && !empty($_POST['aa-category-submitted'])) {
        aa_admin_categories_save();
    }
    else {
    ?>
    <table class="widefat">
            <thead>
		<tr>
		<th>ID</th>
		<th>Amazon Produktgruppenname</th>
		<th>Produktgruppenname</th>
		</tr>
            </thead>
            <tbody>
                <?php
                $rowno = 0;
                
                    foreach ($categories as $category) {
                       $rowno++;
                       $class = ($rowno % 2) ? 'alternate' : '';  
                    
                ?>
                <tr>
                    <td><a href='<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=aa-cat&action=edit&category_id=<?php echo $category->category_id ?>'><?php echo $category->category_id;?></a></td>
                    <td><a href='<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=aa-cat&action=edit&category_id=<?php echo $category->category_id ?>'><?php echo $category->category_englishname;?></a></td>
                    <td><a href='<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=aa-cat&action=edit&category_id=<?php echo $category->category_id ?>'><?php echo $category->category_germanname ;?></a></td>
                </tr>
                <?php } ?>
            </tbody>
    </table>
<?php
    }
}

function aa_admin_category_edit() {
  
        $category = aa_Categories::getCategoryByID($_REQUEST['category_id']);
        ?>
        <h2>Kategorie bearbeiten</h2>
        <div id="poststuff" class="metabox-holder">
            <div id="post-body">
                <div id="post-body-content">
                    <form id="catedit" name="catedit" action="admin.php?page=aa-cat&action=save" method="post">
                    <input type="hidden" name="category_id" value="<?php echo $category->category_id;?>"/>
                    Amazon Bezeichnung (#_ENGLISHNAME): <input readonly="true" name="englishname" style="width: 80%" value="<?php echo $category->category_englishname;?>"/><br/>
                    Deutsche Bezeichnung (#_GERMANNAME): <input name="germanname" style="width: 80%" value="<?php echo $category->category_germanname;?>"/><br/>
                    <input type="submit" id="aa_category_submit" name="Submit" value="speichern"/>
                    <input type="hidden" name="aa-category-submitted" value="1" />
                    </form>
                </div>
            </div>
	</div>
<?php
}

function aa_admin_categories_save()
{
   $category = aa_Categories::getCategoryByID($_POST['category_id']);
   $category->germanname = $_POST['germanname'];
   aa_Categories::updateCategory($category);
   echo 'Kategorie wurde erfolgreich gespeichert';
}
?>
