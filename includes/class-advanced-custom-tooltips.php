<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://codebyshellbot.com
 * @since      1.0.0
 *
 * @package    Advanced_Custom_Tooltips
 * @subpackage Advanced_Custom_Tooltips/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Advanced_Custom_Tooltips
 * @subpackage Advanced_Custom_Tooltips/includes
 * @author     Shellbot <hi@codebyshellbot.com>
 */
class Advanced_Custom_Tooltips {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Advanced_Custom_Tooltips_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Default plugin settings.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $defaults   The default settings array.
	 */
	protected $defaults;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'Advanced Custom Tooltips';
		$this->version = '1.0.0';
		$this->defaults = $this->set_defaults();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		define( 'WPACT_INCLUDES_URL', plugin_dir_url( __FILE__ ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Advanced_Custom_Tooltips_Loader. Orchestrates the hooks of the plugin.
	 * - Advanced_Custom_Tooltips_i18n. Defines internationalization functionality.
	 * - Advanced_Custom_Tooltips_Admin. Defines all hooks for the admin area.
	 * - Advanced_Custom_Tooltips_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-custom-tooltips-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-custom-tooltips-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advanced-custom-tooltips-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advanced-custom-tooltips-public.php';

		$this->loader = new Advanced_Custom_Tooltips_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Advanced_Custom_Tooltips_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Advanced_Custom_Tooltips_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Advanced_Custom_Tooltips_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_defaults() );

		$this->loader->add_action( 'init', $plugin_admin, 'add_tooltip_cpt' );
    $this->loader->add_action( 'init', $plugin_admin, 'register_tooltip_settings_page' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'after_setup_theme', $plugin_admin, 'include_optiontree' );

		$this->loader->add_filter( 'ot_header_logo_link', $plugin_admin, 'filter_header_logo_link' );
    $this->loader->add_filter( 'ot_header_version_text', $plugin_admin, 'filter_header_version_text' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Advanced_Custom_Tooltips_Public( $this->get_plugin_name(), $this->get_version(), $this->get_defaults() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_print_styles', $plugin_public, 'print_css' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'print_tooltip_js' );

		$this->loader->add_filter( 'the_content', $plugin_public,  'content_filter' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Advanced_Custom_Tooltips_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set the default plugin settings.
	 *
	 * @since     1.0.0
	 * @return    array    The default plugin settings.
	 */
	public function set_defaults() {

		return array(
				'auto_linking'	=> 'first',
				'trigger_style'	=> 'underline-dotted',
				'trigger_color'	=> '#000000',
				'tooltip_background_color'	=> '#4c4c4c',
				'tooltip_text_color'	=> '#ffffff',
				'tooltip_border_color'	=> '#000000',
				'tooltip_corner_style'	=> 'square',
		);

	}

	/**
	 * Get the default plugin settings.
	 *
	 * @since     1.0.0
	 * @return    array    The default plugin settings.
	 */
	public function get_defaults() {
		return $this->defaults;
	}

}
