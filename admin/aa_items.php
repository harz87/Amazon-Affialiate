<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function aa_admin_items_page() {
    global $wpdb, $item;
    
    $items = AA_Items::get();

    if (empty ( $items )) {
	echo 'Keine Produkte in Datenbank importiert';
    }
    else {
        ?>
        <table class="widefat">
            <thead>
		<tr>
		<th>Titel</th>
		<th>Hersteller</th>
		<th>Preis</th>
		</tr>
            </thead>
            <tbody>
                <?php
                    $rowno = 0;
                    $event_count = 0;
                    foreach ( $items as $item ) {
                      $rowno++;
                      $class = ($rowno % 2) ? 'alternate' : '';  
                    ?>
                    <tr class="<?php echo trim($class); ?>" id="item_<?php echo $item->item_id ?>">
                        <td>
                            <strong>
				<a class="row-title" href="<?php bloginfo ( 'wpurl' )?>/wp-admin/admin.php?page=aa_edit_item&amp;action=item_edit&amp;item_id=<?php echo $item->item_id ?>"><?php echo ($item->item_title); ?></a>
                            </strong>
                            <div class="row-actions">
				<span class="trash"><a href="<?php bloginfo ( 'wpurl' )?>/wp-admin/admin.php?page=aa_edit_item&action=item_delete&amp;item_id=<?php echo $item->item_id ?>">löschen</a></span>
                            </div>
                        </td>
                        <td>
                            <?php echo $item->item_manufacturer; ?>
                        </td>
                        <td>
                            <?php echo $item->item_price;?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
            </tbody>
        </table>


<?php
    }
}
?>
