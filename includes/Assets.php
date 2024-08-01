<?php

namespace PVCBT;

/**
 * Assets class.
 *
 * Add css for pvcbt-post-view shortcode.
 * 
 * @class Assets
 */
class Assets {

    /**
	 *  __construct
     * 
     * @return void
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
    }

    /**
     * Register scripts and styles
     *
     * @since 1.0.0
     * @return void
     */
    public function register_assets() {
        wp_register_style( 'pvcbt-style', PVCBT_ASSETS . '/css/pvcbt-style.css', array(), PVCBT_VERSION, 'all' );
    }
}