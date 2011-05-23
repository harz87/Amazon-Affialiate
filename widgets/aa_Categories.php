<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of aa_Categories
 *
 * @author h_titz
 */
class AA_Widget extends WP_Widget {
    var $defaults;

    /** constructor */
    function AA_Widget() {
    	$this->defaults = array(
                'title' => 'Kategorien',
    		'format' => '<li>#_CATLINK</li>',
                'all_categories' => 0,
                'all_categories_text' => 'Alle Produktkategorien'
    	);
    	$widget_ops = array('description' => 'Zeigt alle Kategorien mit Produkten an');
        parent::WP_Widget(false, $name = 'Amazonkategorien', $widget_ops);
    }
    
    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        $items_page_id = get_option('dbaa_items_page');
        if(get_the_ID ()==$items_page_id && $items_page_id != 0){
    	$instance = array_merge($this->defaults, $instance);
        echo $args['before_widget'];
	    echo $args['before_title'];
	    echo $instance['title'];
        echo $args['after_title'];
		if ( is_numeric($instance['time_limit']) && $instance['time_limit'] > 0 ){
			$instance['scope'] = date('Y-m-d').",".date('Y-m-t', strtotime('+'.($instance['time_limit']-1).' month'));
		}
		$instance['owner'] = false;

                $categories= aa_Categories::getCategoriesWithProducts();
                echo "<ul>";
		$li_wrap = !preg_match('/^<li>/i', trim($instance['format']));
		if ( count($categories) > 0 ){
                    foreach($categories as $category){
				if( $li_wrap ){
					echo '<li>'. aa_Categories::formatCategory($instance['format'],$category) .'</li>';
				}else{
					echo aa_Categories::formatCategory($instance['format'],$category);
				}
			}
                }
                else{
                    echo '<li>Keine Produkte in Kategorien vorhanden.</li>';
                }
                if ( !empty($instance['all_categories']) ){
			$categories_link = aa_getCategoriesLink($instance['all_categories_text']);
			echo '<li>'.$categories_link.'</li>';
		}
                echo '</ul>';
                echo $args['after_widget'];
            
        }
         }

    function update($new_instance, $old_instance) {
    	foreach($this->defaults as $key => $value){
    		if( empty($new_instance[$key]) ){
    			$new_instance[$key] = $value;
    		}
    	}
    	return $new_instance;
    }

    function form($instance) {
    	$instance = array_merge($this->defaults, $instance);
        ?>
        <p>	
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />

        </p>
        <p>	
            <label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('List item format','dbem'); ?>: </label>
            <textarea rows="5" cols="24" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>"><?php echo $instance['format']; ?></textarea>
	</p>
        <p>
            <label for="<?php echo $this->get_field_id('all_categories'); ?>"><?php echo 'zeige Link zu allen Produktkategorien am Ende'; ?>: </label>
            <input type="checkbox" id="<?php echo $this->get_field_id('all_categories'); ?>" name="<?php echo $this->get_field_name('all_categories'); ?>" <?php echo (!empty($instance['all_categories']) && $instance['all_categories']) ? 'checked':''; ?> >
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('all_categories'); ?>"><?php echo 'Link Text zu allen Produktkategorien'; ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('all_categories_text'); ?>" name="<?php echo $this->get_field_name('all_categories_text'); ?>" value="<?php echo (!empty($instance['all_categories_text'])) ? $instance['all_categories_text']:'Alle Produktkategorien'; ?>" >
        </p>
    <?php
    }


}
add_action('widgets_init', create_function('', 'return register_widget("AA_Widget");'));

?>
