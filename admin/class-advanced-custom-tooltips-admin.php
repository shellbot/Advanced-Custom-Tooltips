<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codebyshellbot.com
 * @since      1.0.0
 *
 * @package    Advanced_Custom_Tooltips
 * @subpackage Advanced_Custom_Tooltips/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Advanced_Custom_Tooltips
 * @subpackage Advanced_Custom_Tooltips/admin
 * @author     Shellbot <hi@codebyshellbot.com>
 */
class Advanced_Custom_Tooltips_Admin {

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
	 * Default plugin settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $defaults   The default settings array.
	 */
	private $defaults;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      array     $defaults    The default plugin settings.
	 */
	public function __construct( $plugin_name, $version, $defaults ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = $defaults;

		add_shortcode( 'act_tooltip', array( $this, 'tooltip_shortcode' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advanced-custom-tooltips-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-custom-tooltips-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add the tooltip custom post type.
	 *
	 * @since 1.0.0
	 */
	function add_tooltip_cpt() {
		$labels = array(
		'name'               => _x( 'Tooltips', 'post type general name', 'sb-act' ),
		'singular_name'      => _x( 'Tooltip', 'post type singular name', 'sb-act' ),
		'menu_name'          => _x( 'Tooltips', 'admin menu', 'sb-act' ),
		'name_admin_bar'     => _x( 'Tooltips', 'add new on admin bar', 'sb-act' ),
		'add_new'            => _x( 'Add New', 'tooltip', 'sb-act' ),
		'add_new_item'       => __( 'Add New Tooltip', 'sb-act' ),
		'new_item'           => __( 'New Tooltip', 'sb-act' ),
		'edit_item'          => __( 'Edit Tooltip', 'sb-act' ),
		'view_item'          => __( 'View Tooltip', 'sb-act' ),
		'all_items'          => __( 'All Tooltips', 'sb-act' ),
		'search_items'       => __( 'Search Tooltips', 'sb-act' ),
		'parent_item_colon'  => __( 'Parent Tooltips:', 'sb-act' ),
		'not_found'          => __( 'No tooltips found.', 'sb-act' ),
		'not_found_in_trash' => __( 'No tooltips found in Trash.', 'sb-act' )
		);

		$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'act_tooltip', $args );
	}

	/**
	 * Tooltip shortcode.
	 *
	 * @since    1.0.0
	 */
	 public function tooltip_shortcode( $atts, $code_content ) {

		 if( !isset( $atts['id'] ) && !isset( $atts['content'] ) ) { //No tooltip ID or content provided, do nothing
		 	return;
		 }

		 $defaults = extract( shortcode_atts(array(
			 'id' => '',
			 'title' => 'Advanced Custom Tooltip',
			 'content' => '',
		 ), $atts, 'act-tooltip-shortcode-atts' ) );

		 if( !isset( $atts['id'] ) ) { //Plain text tooltip
			 if( $code_content ) {
				 $tooltip_text = $code_content;
			 } else {
				 $tooltip_text = $title;
			 }
			 $tooltip_content = $content;
			 return '<span class="tooltip" title="' . $tooltip_content . '">' . $tooltip_text . '</span>';
		 }

		 //ID provided, get this tooltip from db
		 query_posts( 'post_type=act_tooltip&p=' . $id );

		 if ( have_posts()) : while (have_posts()) : the_post();

			 if( $code_content ) {

				 $tooltip_text = $code_content;
			 } else {
				 $tooltip_text = get_the_title();
			 }

			 $tooltip_content = htmlentities( get_the_content() );

		 endwhile; endif; wp_reset_query();

		 return '<span class="tooltip" title="' . do_shortcode( $tooltip_content ) . '">' . $tooltip_text . '</span>';

	 }

	/**
	 * Registers a new global tooltip settings page.
	 *
	 * @since    1.0.0
	 */
	public function register_tooltip_settings_page() {

	  // Only execute in admin & if OT is installed
  	if ( is_admin() && function_exists( 'ot_register_settings' ) ) {

	    // Register the page
    	ot_register_settings(
        array(
      		array(
            'id'              => 'wpact_global_settings',
            'pages'           => array(
              array(
	              'id'              => 'wpact-tooltip-settings',
	              'parent_slug'     => 'edit.php?post_type=act_tooltip',
	              'page_title'      => __( 'Advanced Custom Tooltips - Global Settings', 'wp-craft-blogger' ),
	              'menu_title'      => __( 'Settings', 'wp-craft-blogger' ),
	              'capability'      => 'edit_theme_options',
	              'menu_slug'       => 'wpact-settings',
	              'icon_url'        => null,
	              'position'        => null,
	              'updated_message' => __( 'Settings updated', 'wp-craft-blogger' ),
	              'reset_message'   => __( 'Settings reset', 'wp-craft-blogger' ),
	              'button_text'     => __( 'Save changes', 'wp-craft-blogger' ),
	              'show_buttons'    => true,
	              'screen_icon'     => 'options-general',
	              'contextual_help' => null,
	              'sections'        => array(
	                array(
	                  'id'          => 'wpact-general',
	                  'title'       => __( 'General', 'wp-craft-blogger' ),
	                ),
									array(
	                  'id'          => 'wpact-styles',
	                  'title'       => __( 'Styling', 'wp-craft-blogger' ),
	                ),
	              ),
                'settings'        => array(
									array(
										'id'					=> 'auto_linking',
										'label'				=> __( 'Auto linking', 'advanced-custom-tooltips' ),
										'section'			=> 'wpact-general',
										'type'				=> 'radio',
										'std'					=> $this->defaults['auto_linking'],
										'choices'			=> $this->get_autolink_options(),
									),
									array(
										'id'					=> 'trigger_style',
										'label'				=> __( 'Trigger text style', 'advanced-custom-tooltips' ),
										'section'			=> 'wpact-styles',
										'type'				=> 'select',
										'std'					=> $this->defaults['trigger_style'],
										'choices'			=> array(
												array(
													'label'	=> __( 'Underline', 'advanced-custom-tooltips' ),
													'value' => 'underline',
												),
												array(
													'label'	=> __( 'Dotted underline', 'advanced-custom-tooltips' ),
													'value' => 'underline-dotted',
												),
												array(
													'label'	=> __( 'Highlight', 'advanced-custom-tooltips' ),
													'value' => 'highlight',
												),
												array(
													'label'	=> __( 'None', 'advanced-custom-tooltips' ),
													'value' => 'none',
												),
										),
									),
									array(
										'id'					=> 'trigger_color',
										'label'				=> 'Color for trigger style',
										'section'			=> 'wpact-styles',
										'type'				=> 'colorpicker',
										'std'					=> $this->defaults['trigger_color'],
									),
									array(
										'id'					=> 'tooltip_background_color',
										'label'				=> 'Color for tooltip background',
										'section'			=> 'wpact-styles',
										'type'				=> 'colorpicker',
										'std'					=> $this->defaults['tooltip_background_color'],
									),
									array(
										'id'					=> 'tooltip_text_color',
										'label'				=> 'Color for tooltip text',
										'section'			=> 'wpact-styles',
										'type'				=> 'colorpicker',
										'std'					=> $this->defaults['tooltip_text_color'],
									),
									array(
										'id'					=> 'tooltip_border_color',
										'label'				=> 'Color for tooltip border',
										'section'			=> 'wpact-styles',
										'type'				=> 'colorpicker',
										'std'					=> $this->defaults['tooltip_border_color'],
									),
									array(
										'id'					=> 'tooltip_corner_style',
										'label'				=> 'Tooltip corner style',
										'section'			=> 'wpact-styles',
										'type'				=> 'radio',
										'std'					=> $this->defaults['tooltip_corner_style'],
										'choices'			=> array(
											array(
												'label'		=> 'Rounded',
												'value'		=> 'rounded',
											),
											array(
												'label'		=> 'Square',
												'value'		=> 'square',
											),
										),
									),
								),
	            )
	          )
	        )
		    )
			);

	  }

	}

	/**
	 * Return auto_linking options for use in global and per-tooltip settings.
	 *
	 * @return array $options
	 * @since 1.0.0
	 */
	function get_autolink_options() {

		$options = array(
				array(
					'label' => __( 'Automatically link first instance of each trigger text in the post/page', 'advanced-custom-tooltips' ),
					'value' => 'first',
				),
				array(
					'label' => __( 'Automatically link all instances of trigger text in the post/page', 'advanced-custom-tooltips' ),
					'value' => 'all',
				),
				array(
					'label' => __( 'Never automatically link instances of trigger text in the post/page', 'advanced-custom-tooltips' ),
					'value' => 'off',
				),
		);

		return $options;

	}

	//TODO Next two functions are terribad

	/**
	 * Filter the OptionTree header logo link
	 *
	 * @since 1.0.0
	 */
  function filter_header_logo_link() {

		$screen = get_current_screen();

		if( $screen->id == 'act_tooltip_page_wpact-settings' ) {
			return '';
		} else {
			return '<a href="http://wordpress.org/extend/plugins/option-tree/" target="_blank">OptionTree</a>';
		}

  }

	/**
	 * Filter the OptionTree header version text
	 *
	 * @since 1.0.0
	 */
  function filter_header_version_text() {

		$screen = get_current_screen();

		if( $screen->id == 'act_tooltip_page_wpact-settings' ) {
			return '<a href="http://wordpress.org/plugins/advanced-custom-tooltips" target="_blank">' . $this->plugin_name . ' - v' . $this->version . '</a>';
		} else {
			return 'OptionTree ' . OT_VERSION;
		}

  }

	/**
	 * OptionTree options framework for generating plugin settings page & metaboxes.
	 *
	 * Only needs to load if no other theme/plugin already loaded it.
	 *
	 * @since 0.0.1
	 */
	function include_optiontree() {

		if ( ! class_exists( 'OT_Loader' ) ) {
    	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/option-tree/ot-loader.php';

			/* TODO - probably shouldn't be doing this here */
			add_filter( 'ot_show_pages', '__return_false' );
			add_filter( 'ot_use_theme_options', '__return_false' );
		}

	}

}
