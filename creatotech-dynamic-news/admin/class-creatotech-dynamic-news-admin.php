<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://creatotech.com/
 * @since      1.0.0
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/admin
 * @author     Creatotech <support@creatotech>
 */
class Creatotech_Dynamic_News_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Creatotech_Dynamic_News_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Creatotech_Dynamic_News_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/creatotech-dynamic-news-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Creatotech_Dynamic_News_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Creatotech_Dynamic_News_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/creatotech-dynamic-news-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function creato_news_menu(){

		/**
		 * admin menu added on backend
		 */

		add_menu_page(
			__( 'Dynamic News', 'creatotech-dynamic-news' ),
			'Dynamic News',
			'manage_options',
			'dynamic-news',
			array($this, 'dynamic_news_menu'),
			'dashicons-awards',
			6
		);
	}

	public function dynamic_news_menu(){

		include('partials/creatotech-dynamic-news-admin-display.php');

	}

/*	public function my_daily_event_news_function(){

		include('partials/cron-creatotech-dynamic-news-admin-display.php');

	}
	*/
	public function creato_news_post_type(){

		 $labels = array(
		    "name"          => __( 'Dynamic News', '' ),
		    "singular_name" => __( 'Dynamic News', '' ),
		  );
		  $args   = array(
		    "label"               => __( 'Dynamic News', '' ),
		    "labels"              => $labels,
		    "description"         => "",
		    "public"              => true,
		    "publicly_queryable"  => true,
		    "show_ui"             => true,
		    "show_in_rest"        => false,
		    "rest_base"           => "",
		    "has_archive"         => false,
		    "show_in_menu"        => false,
		    "show_in_nav_menus"   => true,
		    "exclude_from_search" => false,
		    "capability_type"     => "post",
		    "map_meta_cap"        => true,
		    "hierarchical"        => false,
		    "rewrite"             => array( "slug" => "news", "with_front" => true ),
		    "query_var"           => true,
		    "menu_position"       => 3,
		    "menu_icon"           => "dashicons-text-page",
		    "supports"            => array( "title", "editor", "thumbnail" ),
		  );
		  register_post_type( "dynamic_news", $args );

	}
	
}