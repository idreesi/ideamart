<?php /* Dash Categories */

if ( ! defined( 'ABSPATH' ) ) exit; 

class Dash_Categories extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'dash_categories', 
			esc_html__('Dash Categories', 'dashstore'), 
			array('description' => esc_html__( "Dash Store special widget. Display configurable list of categories on your site.", 'dashstore' ), ) 
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => 'Categories',
			'cats_count' => false,
			'show_img' => false,
			'hierarchical' => false,
			'collapsing' => false,
			'cats_type' => 'category',
			'sortby' => 'name',
			'order' => 'DESC',
			'exclude_cats' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

		<p>
		    <label for ="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title: ','dashstore'); ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
		    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name('cats_count') ); ?>" <?php if (esc_attr( $instance['cats_count'] )) {
		                    echo 'checked="checked"';
		                } ?> class=""  size="4"  id="<?php echo esc_attr($this->get_field_id('cats_count')); ?>" />
		    <label for ="<?php echo esc_attr( $this->get_field_id('cats_count') ); ?>"><?php esc_html_e('Show count for categories','dashstore'); ?></label>
		</p>
		<p>
		    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_img') ); ?>" <?php if (esc_attr( $instance['show_img'] )) {
		                    echo 'checked="checked"';
		                } ?> class=""  size="4"  id="<?php echo esc_attr($this->get_field_id('show_img')); ?>" />
		    <label for ="<?php echo esc_attr( $this->get_field_id('show_img') ); ?>"><?php esc_html_e('Show images for categories','dashstore'); ?></label>
		</p>
		<p>
		    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name('hierarchical') ); ?>" <?php if (esc_attr( $instance['hierarchical'] )) {
		                    echo 'checked="checked"';
		                } ?> class=""  size="4"  id="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>" />
		    <label for ="<?php echo esc_attr( $this->get_field_id('hierarchical') ); ?>"><?php esc_html_e('Show hierarchy','dashstore'); ?></label>
		</p>
		<p>
		    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name('collapsing') ); ?>" <?php if (esc_attr( $instance['collapsing'] )) {
		                    echo 'checked="checked"';
		                } ?> class=""  size="4"  id="<?php echo esc_attr($this->get_field_id('collapsing')); ?>" />
		    <label for ="<?php echo esc_attr( $this->get_field_id('collapsing') ); ?>"><?php esc_html_e('Collapsing categories','dashstore'); ?></label>
		    <br /><small><?php esc_html_e('works only when "Show hierarchy" checked.', 'dashstore'); ?></small>
		</p>
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id('cats_type') ); ?>"><?php esc_html_e('Categories type:','dashstore'); ?> 
		        <select class='widefat' id="<?php echo esc_attr($this->get_field_id('cats_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('cats_type') ); ?>">
		          <option value='category'<?php echo (esc_attr($instance['cats_type']=='category'))?' selected="selected"':''; ?>><?php esc_html_e('Post Categories', 'dashstore'); ?></option>
		          <option value='product_cat'<?php echo (esc_attr($instance['cats_type']=='product_cat'))?' selected="selected"':''; ?>><?php esc_html_e('Product Categories', 'dashstore'); ?></option> 
		        </select>                
		    </label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('sortby') ); ?>"><?php esc_html_e('Sort by:','dashstore'); ?> 
		        <select class='widefat' id="<?php echo esc_attr($this->get_field_id('sortby')); ?>" name="<?php echo $this->get_field_name('sortby'); ?>">
		          <option value='ID'<?php echo (esc_attr($instance['sortby']=='ID'))?' selected':''; ?>><?php esc_html_e('ID', 'dashstore'); ?></option>
		          <option value='name'<?php echo (esc_attr($instance['sortby']=='name'))?' selected="selected"':''; ?>><?php esc_html_e('Name', 'dashstore'); ?></option> 
		          <option value='slug'<?php echo (esc_attr($instance['sortby']=='slug'))?' selected="selected"':''; ?>><?php esc_html_e('Slug', 'dashstore'); ?></option> 
		        </select>                
		    </label>
		</p>
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id('order') ); ?>"><?php esc_html_e('Order:','dashstore'); ?> 
		        <select class='widefat' id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr( $this->get_field_name('order') ); ?>">
		          <option value='ASC'<?php echo (esc_attr($instance['order']=='ASC'))?' selected="selected"':''; ?>><?php esc_html_e('Ascending', 'dashstore'); ?></option>
		          <option value='DESC'<?php echo (esc_attr($instance['order']=='DESC'))?' selected="selected"':''; ?>><?php esc_html_e('Descending', 'dashstore'); ?></option> 
		        </select>                
		    </label>
		</p>
		<p>
		    <label for ="<?php echo esc_attr( $this->get_field_id('exclude_cats') ); ?>"><?php esc_html_e('Exclude Category (ID): ','dashstore'); ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('exclude_cats')); ?>" name="<?php echo esc_attr( $this->get_field_name('exclude_cats') ); ?>" value="<?php echo esc_attr($instance['exclude_cats']); ?>"/>
		    <small><?php esc_html_e('category IDs, separated by commas.', 'dashstore'); ?></small>
		</p>

	<?php }

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cats_count'] = $new_instance['cats_count'];
		$instance['show_img'] = $new_instance['show_img'];
		$instance['hierarchical'] = $new_instance['hierarchical'];
		$instance['collapsing'] = $new_instance['collapsing'];
		$instance['cats_type'] = strip_tags( $new_instance['cats_type'] );
		$instance['sortby'] = strip_tags( $new_instance['sortby'] );
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['exclude_cats'] = strip_tags( $new_instance['exclude_cats'] );

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$show_count = ( isset($instance['cats_count']) ? $instance['cats_count'] : false );
		$show_img = ( isset($instance['show_img']) ? $instance['show_img'] : false );
		$hierarchical = ( isset($instance['hierarchical']) ? $instance['hierarchical'] : false );
		$collapsing = ( isset($instance['collapsing']) ? $instance['collapsing'] : false );
		$cats_type = ( isset($instance['cats_type']) ? $instance['cats_type'] : 'category' );
		$sortby = ( isset($instance['sortby']) ? $instance['sortby'] : 'name' );
		$order = ( isset($instance['order']) ? $instance['order'] : 'DESC' );
		$exclude_cats = ( isset($instance['exclude_cats']) ? $instance['exclude_cats'] : '' );

		global $wp_query, $post, $product;	

		// Setup Current Category
		$current_cat   = false;
		$cat_ancestors = array();

		if ( is_tax('product_cat') || is_category() ) {
			$current_cat   = $wp_query->queried_object;
			$cat_ancestors = get_ancestors( $current_cat->term_id, $cats_type );
		} 
		
		echo $before_widget;
		echo $before_title;
		if ($title) echo esc_attr($title);
		echo $after_title;

		$catsWalker = new Dash_Cats_List_Walker();
      	$catsWalker->show_img = $show_img;
      	$catsWalker->collapsing = $collapsing;

	    $args = array(
			'orderby'            => $sortby,
			'order'              => $order,
			'style'              => 'list',
			'show_count'         => $show_count,
			'hide_empty'         => true,
			'exclude'            => $exclude_cats,
			'hierarchical'       => $hierarchical,
			'title_li'           => '',
			'show_option_none'   => esc_html__( 'No categories', 'dashstore' ),
			'taxonomy'           => $cats_type,
	    );
	    $args['walker'] = $catsWalker;
	    $args['current_category'] = ( $current_cat ) ? $current_cat->term_id : '';
		$args['current_category_ancestors'] = $cat_ancestors;

		echo '<ul class="pt-categories">';

		wp_list_categories( $args );

		echo '</ul>';

        echo $after_widget;
       
    }

}

if (class_exists('Woocommerce')) {
	add_action( 'widgets_init', create_function( '', 'register_widget( "Dash_Categories" );' ) );
}

/* Special Walker Class for Categories */
class Dash_Cats_List_Walker extends Walker {

	private $curItem;

	var $tree_type = 'product_cat';
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$data = get_object_vars($this->curItem);
		$parent_id = $data['term_id'];
		$col_class = '';

		if ($this->collapsing = true) {
			$col_class=' collapse';
			if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $parent_id, $args['current_category_ancestors'] ) ) {
			$col_class .= ' in'; }
		}
		
		$output .= $indent . '<ul id="children-of-' . esc_attr($parent_id) . '" class="children' . esc_attr($col_class) . '">';
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= $indent . '</ul>';
	}

	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$this->curItem = $cat;
    
		/* Adding extra classes if needed */
		$output .= '<li class="cat-item cat-item-' . esc_attr($cat->term_id);
		if ( $args['current_category'] == $cat->term_id ) {
			$output .= ' current-cat';
		}
		if ( $args['has_children'] && $args['hierarchical'] ) {
			$output .= ' cat-parent';
		}
		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
			$output .= ' current-cat-parent';
		}
		$output .=  '">';

		/* Output categorie img */
		if ($this->show_img == true) {
			$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_image( $thumbnail_id, 'dash-cat-thumb', false );
		}

		if ($image && $image!='') {
			$output .= '<span class="cat-img-wrap">'.$image.'</span>';
		}

		/* Get link to category & Adding extra data to cat anchor */
		$term_link = get_term_link( (int) $cat->term_id, $cat->taxonomy );
		if ( is_wp_error( $term_link ) ) {
        	exit;
    	}
		$output .=  '<a href="' . esc_url($term_link) . '">' . esc_attr($cat->name) . '</a>';

		/* Adding show subcategories button */
		if ($this->collapsing = true) {
			$anchor = '';
	    	if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
	    		$anchor = '<a href="#children-of-' . esc_attr($cat->term_id) . '" class="show-children" data-toggle="collapse" aria-controls="children-of-' . esc_attr($cat->term_id) . '" aria-expanded="true"><span></span></a>';
	    	}
	    	if ( $args['has_children'] && $args['hierarchical'] ) {
				$anchor = '<a href="#children-of-' . esc_attr($cat->term_id) . '" class="show-children collapsed" data-toggle="collapse" aria-controls="children-of-' . esc_attr($cat->term_id) . '" aria-expanded="false"><span></span></a>';
			}
			$output .= $anchor;
		}

		/* Adding counter if needed */
		if ( $args['show_count'] ) {
			$output .= ' <span class="count">(' . esc_attr($cat->count) . ')</span>';
		}
	}

	public function end_el( &$output, $cat, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

	public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		if ( ! $element || 0 === $element->count ) {
			return;
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
