<?php 
/*
 * Plugin Name: Post View Count By Taslim
 * Plugin URI:  http://taslimhossain.com/plugins/post-view-count-by-taslim/
 * Description: This plugin allows you to easily display how many times a post had been viewed. <strong>[pvcbt-post-view]</strong> OR <strong>[pvcbt-post-view id="post_id"]</strong>. Add post id, it will display views for that post. Default: current post id.
 * Version:     1.0.0
 * Author:      taslim
 * Author URI:  https://taslimhossain.com/
 * Text Domain: post-view-count-by-taslim
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Post view count by taslim main class.
 */
final class PostViewCountByTaslim {
    
    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
	 *  __construct
     * 
     * Sets up all the appropriate hooks and actions within our plugin.
     * 
     * @since 1.0.0
	 * @return void
     */
    private function __construct() {

        // Define constant.
        $this->define_constants();

        // initialize the plugin
        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes a singleton instance
     *
     * @since 1.0.0
     * @return \PostViewCountByTaslim
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
	 * @since 1.0.0
	 * @return void
     */
    public function define_constants() {

        if ( ! defined( 'PVCBT_VERSION' ) ) {
            define( 'PVCBT_VERSION', self::version );
        }
        
        if ( ! defined( 'PVCBT_FILE' ) ) {
            define( 'PVCBT_FILE', __FILE__ );
        }
        
        if ( ! defined( 'PVCBT_PATH' ) ) {
            define( 'PVCBT_PATH', __DIR__ );
        }
        
        if ( ! defined( 'PVCBT_URL' ) ) {
            define( 'PVCBT_URL', plugins_url( '', PVCBT_FILE ) );
        }

        if ( ! defined( 'PVCBT_ASSETS' ) ) {
            define( 'PVCBT_ASSETS', PVCBT_URL . '/assets' );
        }
    }

    /**
     * Initialize the plugin
     *
     * @since 1.0.0
     * @return void
     */
    public function init_plugin() {

        // Enqueue scripts.
        new PVCBT\Assets();
        
        // Check if it's admin.
        if ( is_admin() ) {
            new PVCBT\Admin();
        } else {
            new PVCBT\Frontend();
        }
    }
}

/**
 * Initialize Post Views Count.
 *
 * @since 1.0.0
 * @return object
 */
if ( ! function_exists( 'pvcbt_post_view_count' ) ) {
    function pvcbt_post_view_count() {
        return PostViewCountByTaslim::init();
    }
}

// Run the plugin.
pvcbt_post_view_count();