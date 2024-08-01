<?php

namespace PVCBT;

/**
 * Frontend class.
 *
 * @class Frontend
 */
class Frontend {

    /**
	 *  __construct
	 * 
	 * @return void
     */
    function __construct() {

        // Sets up all the appropriate hooks and actions within our plugin.
        $this->dispatch_actions();
    }

    /**
     * Dispatch and bind actions
     *
     * @since 1.0.0
     * @return void
     */
    public function dispatch_actions() {
        add_action( 'wp_head', array( $this, 'count_post_view') );
        add_action( 'after_setup_theme', array( $this, 'register_shortcode' ) );
    }

	/**
	 * Register pvcbt-post-view shortcode function.
	 *
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'pvcbt-post-view', array( $this, 'render_post_views_shortcode' ) );
	}

	/**
	 * Count post visit.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function count_post_view( ) {

        // skip special requests
        if ( is_preview() || is_feed() || is_trackback() || is_favicon() || is_customize_preview() ){
            return;
        }

        // Check if it's a single post page.
        if ( is_single() ) {
            // get current post id
            $post_id = (int) get_the_ID();

            // Default post vewe number
            $post_views = 0;

            // Post Meta Key.
            $meta_key = 'pvcbt_count';

            // Get post meta value.
            if ( !$post_views = get_post_meta( $post_id, $meta_key, true ) ) {
                // If the count does not exist, set it to zero.
                $post_views = 0;
            }

            // increment it by 1.
            $post_views = (int) $post_views + 1;

            // save page view count value in meta field.
            update_post_meta( $post_id, $meta_key, $post_views );
        }
	}

	/**
	 * Post views shortcode function.
	 *
	 * @param array $atts
     * @param  string $content
     * @since 1.0.0
	 * @return string
	 */
    public function render_post_views_shortcode( $atts , $content = null ) {

        // add css for this shortcode layout.
        wp_enqueue_style( 'pvcbt-style' );

        // Attributes
        $atts = shortcode_atts(
            array(
                'id' => 0,
            ),
            $atts,
            'pvcbt-post-view'
        );

        $post_id = isset( $atts['id'] ) ? $atts['id'] : 0;

        if( $post_id === 0) {
            // get current post id
            $post_id = (int) get_the_ID();
        }

        // Get post view value form post meta.
        $total_view = get_post_meta( $post_id, 'pvcbt_count', true );

        // Check if have already post view meta value exist. if not set 0 by default.
        if(empty($total_view)) {
            $total_view = 0;
        }

        // use number format.
		$total_view = apply_filters( 'pvcbt_post_view_format', number_format_i18n( $total_view ) );

        $post_view_title = __( 'Post Views', 'post-view-count-by-taslim' );
        $post_view_label = __( 'Total views', 'post-view-count-by-taslim' );

        $output  = '<div class="post-veiw-wrap">';
        $output .= '<span class="post-veiw-widget-title">'. $post_view_title .'</span>';
        $output .= '<span class="post-veiw-label">'. $post_view_label .'</span>';
        $output .= '<span class="post-veiw-number">'. $total_view .'</span>';
        $output .= '</div>';
        
        // Return code
        return apply_filters( 'get_pvcbt_post_view', $output );
    }
}
