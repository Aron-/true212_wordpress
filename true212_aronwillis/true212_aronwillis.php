<?php
/**
 * Plugin Name: True 212 WordPress Development Exercise - Aron Willis
 * Description: To demonstrate the functionality required. Procedural.
 * Version: 1.0.0
 * Author: Aron Willis
 */

// Register and load the widget
add_action( 'widgets_init', 'true212_author_load_widget' );

function true212_author_load_widget() {
	register_widget( 'true212_author_widget' );
}

// Create the widget
class true212_author_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Widget Base ID
			'true212_author_widget', 

			// Widget name in UI
			__('Author List Widget', 'author_widget'), 

			// Widget description
			array( 'description' => __( 'Widget to list authors.', 'author_widget' ), ) 
		);
	}

	// Widget Frontend
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// Here is the simple construct to display the site authors.
		echo __( 'The authors for this site are listed below.', 'author_widget' );

		// Standard arguments for the function wp_list_authors for clarity and further customisation.
		$authorargs = array(
			'orderby'       => 'name', 
			'order'         => 'ASC', 
			'number'        => null,
			'optioncount'   => false, 
			'exclude_admin' => true, 	// Exclude admin users.
			'show_fullname' => true,
			'hide_empty'    => false,	// Show all authors regardless of whether they have published content.
			'echo'          => false,
			'feed'          => '', 
			'feed_image'    => '',
			'feed_type'     => '',
			'style'         => 'list',
			'html'          => true,
			'exclude'       => '',
			'include'       => ''
		);

		// Get the result from the cache if it's present.
		$result = wp_cache_get( 'the-authors' );

		// If it's not present then generate authors list and cache it.
		if ( false === $result ) {
			$result = wp_list_authors( $authorargs );
			wp_cache_set('the-authors',$result,'','300');
		} 
		echo '<ul>' . $result . '</ul>';

		echo $args['after_widget'];
	}
		
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'The Authors', 'author_widget' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
	
	// Update widget instance
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

}