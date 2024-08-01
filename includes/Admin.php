<?php

namespace PVCBT;

/**
 * Admin class.
 *
 * Admin page posts list column related all functions and hooks.
 * 
 * @class Admin
 */
class Admin {

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
        // Add post view count columns to post list.
        add_action( 'manage_posts_columns', array( $this, 'add_post_view_count_column_head' ), 10, 1 );

        // Add view count value to post view count column.
		add_action( 'manage_posts_custom_column', array( $this, 'add_post_view_count_column_content' ), 10, 2 );

        // Sortable post view count column.
		add_filter( 'manage_edit-post_sortable_columns', array( $this, 'post_count_sortable_column' ), 10, 1 );

		//Sort posts by post view count value.
		add_action( 'pre_get_posts', array( $this, 'sort_posts_by_post_view_count_value' ), 10, 1 );

		// Delete post view meta value when post is deleted.
		add_action( 'deleted_post', array ( $this, 'delete_post_views' ), 10, 1 );
    }

	/**
	 * Add post view columns to post list
	 *
	 * @param array $columns
	 * @since 1.0.0
	 * @return array
	 */
	public function add_post_view_count_column_head( $columns ) {
		// Add new column.
		$columns['pvcbt_post_view_count'] = __( 'View Count', 'post-view-count-by-taslim' );

		return $columns;
	}

	/**
	 * Add post view count value to post view column.
	 *
	 * @param string $column
	 * @param int    $post_id
	 * @since 1.0.0
	 * @return void
	 */
	public function add_post_view_count_column_content( $column, $post_id ) {
		// Show post view count.
		if ( 'pvcbt_post_view_count' === $column ) {
			// get post view count.
			$count = get_post_meta( $post_id, 'pvcbt_count', true );

			// Check if have already post view meta value exist. if not set 0 by default.
			if(empty($count)) {
				$count = 0;
			}

			// use number format.
			$count = apply_filters( 'pvcbt_post_view_format', number_format_i18n( $count ) );

			// Display post view count.
			echo "<div class='pvcbt_post_view_column_wrap'>";

			if ( $count ) {
                echo "<p class='pvcbt_view_number'> " . esc_html( $count ) . ' ' . esc_html( __( 'Views', 'post-view-count-by-taslim' ) ) . '</p>';
			} else {
                echo "<p  class='pvcbt_view_number'> " . esc_html( __( 'No Views', 'post-view-count-by-taslim' ) ) . '</p>';
			}

			echo '</div>';
		}
	}

	/**
	 * Sortable post view count column
	 *
	 * @param array $columns
	 * @since 1.0.0
	 * @return array
	 */
	public function post_count_sortable_column( $columns ) {
		// Post view count column args for shorting.
		$columns['pvcbt_post_view_count'] = 'pvcbt_post_view_count';

		return $columns;
	}

	/**
	 * Sort posts by post view count value
	 *
	 * @param WP_Query $query
	 * @since 1.0.0
	 * @return void
	 */
	public function sort_posts_by_post_view_count_value( $query ) {

		$orderby = $query->get( 'orderby' );

		// Sort posts by post view count value.
		if ( 'pvcbt_post_view_count' === $orderby ) {
			$query->set( 'meta_key', 'pvcbt_count' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	/**
	 * Remove post views value from database when post is deleted.
	 *
	 * @global object $wpdb
	 *
	 * @param int $post_id
	 * @since 1.0.0
	 * @return void
	 */
	public function delete_post_views( $post_id ) {

		if( ! $post_id ) {
			return;
		}

		delete_post_meta( $post_id, 'pvcbt_count' );
	}

}
