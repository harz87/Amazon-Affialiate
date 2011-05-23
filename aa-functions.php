<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 *
 * @param string $title
 * @param string $name
 * @param string $description
 * generates a textarea with a given title, name, and describition and value of get_option(name)
 */
function aa_options_textarea($title, $name, $description, $default = null) {
	?>
	<tr valign="top" id='<?php echo $name;?>_row'>
		<th scope="row"><?php echo $title ?></th>
			<td>
				<textarea name="<?php echo $name ?>" id="<?php echo $name ?>" rows="6" cols="60"><?php echo htmlspecialchars(get_option($name), ENT_QUOTES);?></textarea><br/>
				<em><?php echo $description; ?></em>
			</td>
                        <?php if(isset($default)):?>
                        <td>
                           <?php
                            $default = get_option($default);
                            $default = preg_replace(array('/</','/>/'), array('&lt;','&gt;'), $default);
                           echo $default;
                            
                           ?>
                        </td>
                        <?php endif; ?>
		</tr>
	<?php
}
/**
 *
 * @param <string> $title
 * @param <string> $name
 * @param <string> $description
 * generates a input with a given title, name, and describition and value of get_option(name)
 */
function aa_options_input_text($title, $name, $description) {
	?>
	<tr valign="top" id='<?php echo $name;?>_row'>
		<th scope="row"><?php echo $title ?></th>
	    <td>
			<input name="<?php echo $name ?>" type="text" id="<?php echo $title ?>" style="width: 95%" value="<?php echo htmlspecialchars(get_option($name), ENT_QUOTES); ?>" size="45" /><br />
			<em><?php echo $description; ?></em>
		</td>
	</tr>
	<?php
}
/**
 *
 * @param <string> $title
 * @param <string> $name
 * @param <array> $list
 * @param <string> $description
 */
function aa_options_select($title, $name, $list, $description) {
	$option_value = get_option($name);
	if( $name == 'dbaa_items_page' && !is_object(get_page($option_value)) ){
		$option_value = 0; //Special value
	}
	?>
   	<tr valign="top" id='<?php echo $name;?>_row'>
   		<th scope="row"><?php echo $title; ?></th>
   		<td>
			<select name="<?php echo $name; ?>" >
				<?php foreach($list as $key => $value) : ?>
 				<option value='<?php echo $key ?>' <?php echo ("$key" == $option_value) ? "selected='selected' " : ''; ?>>
 					<?php echo $value; ?>
 				</option>
				<?php endforeach; ?>
			</select> <br/>
			<em><?php echo $description; ?></em>
		</td>
   	</tr>
	<?php
}

function aa_input_text($title, $name, $value, $description='', $readonly=FALSE) {
    $attr = '';
    if($readonly){
        $attr = 'readonly="true"';        
    }
    ?>
	<tr valign="top" id='<?php echo $name;?>_row'>
		<th scope="row"><?php echo $title ?></th>
	    <td>
			<input name="<?php echo $name ?>" <?php echo $attr; ?> type="text" id="<?php echo $title ?>" style="width: 95%" value="<?php echo $value ?>" size="45" />
			<em><?php echo $description; ?></em>
		</td>
	</tr>
	<?php
}

function aa_features_input_text($title, $name, $value, $count,$description='', $readonly=FALSE) {
    $attr = '';
    if($readonly){
        $attr = 'readonly="true"';
    }
    $html = '';
    $html .= '<th scope="row">'.$title."</th>";
    $html .= '<td><input type="text" style="width:95%" size="45" name="'.$name.'" ' . $attr .' id="'.$title.'" value=""/>';
    $html .= '<em>'.$description.'</em></td>';
    $html .= '<td><input type="button" value="+" onclick="addFeatureRow()"/></td>';
    ?>

	<tr valign="top" id='<?php echo $name;?>_row'>
		<th scope="row"><?php echo $title ?></th>
	    <td>
			<input name="<?php echo $name ?>" <?php echo $attr; ?> type="text" id="<?php echo $name ?>" style="width: 95%" value="<?php echo $value ?>" size="45" />
			<em><?php echo $description; ?></em>
		</td>
                <td>
                    <input type="button" value="+" onclick="addFeatureRow(<?php echo $count?>)"/>
                </td>
	</tr>
	<?php
        return $html;
}

function aa_textarea($title, $name, $value, $description='', $default = null) {
	?>
	<tr valign="top" id='<?php echo $name;?>_row'>
		<th scope="row"><?php echo $title ?></th>
			<td>
				<textarea name="<?php echo $name ?>" id="<?php echo $name ?>" rows="6" cols="60"><?php echo $value;?></textarea>
				<em><?php echo $description; ?></em>
			</td>
                        <?php if(isset($default)):?>
                        <td>
                           <?php
                            $default = get_option($default);
                            $default = preg_replace(array('/</','/>/'), array('&lt;','&gt;'), $default);
                           echo $default;

                           ?>
                        </td>
                        <?php endif; ?>
		</tr>
	<?php
}

function aa_select($title, $name, $list, $selected='', $description='') {
	?>
   	<tr valign="top" id='<?php echo $name;?>_row'>
   		<th scope="row"><?php echo $title; ?></th>
   		<td>
			<select name="<?php echo $name; ?>" >
				<?php foreach($list as $key => $value) : ?>
 				<option value='<?php echo $key ?>' <?php echo ("$key" == $selected) ? "selected='selected' " : ''; ?>>
 					<?php echo $value; ?>
 				</option>
				<?php endforeach; ?>
			</select> <br/>
			<em><?php echo $description; ?></em>
		</td>
   	</tr>
	<?php
}

function aa_import_input_text($title, $name, $value, $readonly=FALSE, $description='') {
    $attr = '';
    if($readonly){
        $attr = 'readonly="true"';        
    }
    ?>
	<tr valign="top" class='<?php echo $name;?>_row'>
            <th scope="row"><?php echo $title ?></th>
            <td>
		<input name="<?php echo $name ?>" <?php echo $attr;?> type="text" id="<?php echo $title ?>" style="width: 95%" value="<?php echo $value ?>" size="45" />
		<em><?php echo $description; ?></em>
            </td>
            <td>
		<?php if(!$readonly) echo $value; ?>
            </td>
	</tr>
	<?php
}

function aa_import_hidden_input($name,$value){
        if (isset ($value)){
        ?>
        <input type="hidden" name="<?php echo $name;?>" value="<?php echo $value; ?>"/>
        <?php
        }
}

function aa_import_main_photo($name,$titleTag,$filename,$src){
    ?>
    <tr valign="top" class='<?php echo $name;?>_row'>
        <td>
            <img alt="<?php echo $titleTag ?>" src="<?php echo $src; ?>"/>
            <input type="hidden" name="pictureUrl" value="<?php echo $src ?>"/>
        </td>
        <td>
            TitleTag:<input type="text" name="pictureTitleTag" style="width: 95%" value="<?php echo $titleTag; ?>"/>
        </td>
        <td>
            Filename:<input type="text" name="pictureFilename" style="width: 65%" value="<?php echo $filename;?>"/>
        </td>
    </tr>
    <?php
}

function aa_import_variant_photo($name,$titleTag,$src,$index){
    ?>
    <tr valign="top" class='<?php echo $name;?>_row'>
        <td>
            <img alt="<?php echo $titleTag ?>" src="<?php echo $src; ?>"/>
            <input type="hidden" name="<?php echo 'pictureUrl'.$index?>" value="<?php echo $src ?>"/>
        </td>
        <td>
            TitleTag:<input type="text" name="<?php echo 'pictureTitleTag'.$index?>" style="width: 95%" value="<?php echo $titleTag; ?>"/
        </td>
        <td>
            <input type="checkbox" name="<?php echo 'pictureCheck'.$index?>"/>
        </td>
    </tr>
    <?php
}

function aa_import_textarea($title, $name, $value, $description='', $default = null) {
	?>
	<tr valign="top" class='<?php echo $name;?>_row'>
		<th scope="row"><?php echo $title ?></th>
			<td>
				<textarea name="<?php echo $name ?>" id="<?php echo $name ?>" rows="6" cols="60"><?php echo $value;?></textarea>
				<em><?php echo $description; ?></em>
			</td>
                        <?php if(isset($default)):?>
                        <td>
                           <?php
                            $default = get_option($default);
                            $default = preg_replace(array('/</','/>/'), array('&lt;','&gt;'), $default);
                           echo $default;

                           ?>
                        </td>
                        <?php endif; ?>
		</tr>
	<?php
}
/**
 *
 * @param string $text
 * @return string
 * returns a a href statement to All Categories with the given text
 */
function aa_getCategoriesLink($text = ''){
    $text = ($text == '') ? 'Alle Produktkategorien' : $text;
    return "<a href='".AA_URI."/categories' title='$text'>$text</a>";
}

/**
 * function executed every hour on cron job
 */
function updateItemsDataHourly(){
    
    $appid = get_option('dbaa_amazon_appid');
    $coutryCode= get_option('dbaa_amazon_coutnrycode');
    $secretKey = get_option('dbaa_amazon_secretkey');
    $associatetag = get_option('dbaa_amazon_associatetag');
    if($appid != '' && $coutryCode != ''&& $secretKey != '') {
        $amazon = new Zend_clicks_Service_Amazon($appid, $coutryCode,$secretKey);
        $results = AA_Items::get();
        foreach ($results as $result){
             $amazonresults=$amazon->itemLookup($result->item_asin,array('AssociateTag' => $associatetag, 'ResponseGroup' => 'Small, OfferFull, ItemAttributes'));
             foreach ($amazonresults as $amazonresult){
                 $vk = 'bla';
                    if(isset ($amazonresult->Offers->Offers)) {
                        $vk = $amazonresult->Offers->Offers[0]->FormattedPrice;
                    }
                    elseif(isset ($amazonresult->FormattedPrice)) {
                        $vk = $amazonresult->FormattedPrice;
                    }

                    if ($result->item_price != $vk){
                       AA_Item::updatePrice($vk, $result->item_id);
                    }
             }
        }
    }
}

?>
