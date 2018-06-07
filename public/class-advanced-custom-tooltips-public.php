<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codebyshellbot.com
 * @since      1.0.0
 *
 * @package    Advanced_Custom_Tooltips
 * @subpackage Advanced_Custom_Tooltips/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Custom_Tooltips
 * @subpackage Advanced_Custom_Tooltips/public
 * @author     Shellbot <hi@codebyshellbot.com>
 */
class Advanced_Custom_Tooltips_Public {

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
	 * Global plugin settings.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $version    The current version of this plugin.
	 */
	public $global_settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      array     $defaults    The default plugin settings.
	 */
	public function __construct( $plugin_name, $version, $defaults ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = $defaults;
		$this->global_settings = ( get_option( 'wpact_global_settings' ) ? array_merge( $defaults, get_option( 'wpact_global_settings' ) ) : $defaults );
                
                add_shortcode( 'act_tooltip', array( $this, 'tooltip_shortcode' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'tooltipster-css', WPACT_INCLUDES_URL . 'tooltipster/css/tooltipster.css' );
                wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advanced-custom-tooltips-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'tooltipster-js', WPACT_INCLUDES_URL . 'tooltipster/js/jquery.tooltipster.min.js', array( 'jquery' ), '', true );

	}

	/**
	 * Check for tooltip triggers within post content.
	 *
	 * @return string $content
	 * @since 1.0.0
	 */
	function content_filter( $content ) {

		//We don't need to run this if auto-linking is turned off
		if( $this->global_settings['auto_linking'] == 'off' ) {
				return $content;
		}

		$tooltips = $this->get_tooltips();

		//Filter content and add tooltip markup
		switch( $this->global_settings['auto_linking'] ) {
			case 'all':
				foreach( $tooltips as $tooltip ) {
					$content = str_ireplace( $tooltip->post_title, '<span class="act-tooltip" title="' . $this->format_tooltip_content( $tooltip->post_content ) . '">' . $tooltip->post_title . '</span>', $content );
				}
			break;
			case 'first':
				foreach( $tooltips as $tooltip ) {

					$pos = stripos( $content, $tooltip->post_title );
					if ($pos !== false) {
						$content = substr_replace( $content, '<span class="act-tooltip" title="' . $this->format_tooltip_content( $tooltip->post_content ) . '">' . $tooltip->post_title . '</span>', $pos, strlen( $tooltip->post_title ) );
					}
				}
			break;
		}

		return $content;

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
			 return '<span class="act-tooltip" title="' . $this->format_tooltip_content( $tooltip_content ) . '">' . $tooltip_text . '</span>';
		 }

		 //ID provided, get this tooltip from db
		 query_posts( 'post_type=act_tooltip&p=' . $id );

		 if ( have_posts()) : while (have_posts()) : the_post();

			 if( $code_content ) {
				 $tooltip_text = $code_content;
			 } else {
				 $tooltip_text = get_the_title();
			 }
                         
			 $tooltip_content = $this->format_tooltip_content( get_the_content() );

		 endwhile; endif; wp_reset_query();

		 return '<span class="act-tooltip" title="' . $tooltip_content . '">' . $tooltip_text . '</span>';

	 }
         
        /**
         * Format tooltip content for display
         * 
         * @since 1.0.2
         */
         function format_tooltip_content( $content ) {
             return htmlentities( wpautop( do_shortcode( $content ) ) );
         }

	/**
	 * Retrieve all tooltips from the database.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function get_tooltips() {

		$query = array (
                        'post_type' => 'act_tooltip',
                        'posts_per_page' => -1,
		);

		return get_posts( $query );

	}

	/**
	 * Print tooltip CSS.
	 *
	 * @since 1.0.0
	 */
	function print_css() {
	?>
		<style type="text/css">
			/* Tooltip trigger styles */
			span.tooltipstered {
                                color: <?php echo $this->global_settings['trigger_text_color']; ?>;
				<?php
				switch( $this->global_settings['trigger_style'] ) {

					case 'underline':
						?>
						border-bottom: 1px solid;
						border-color: <?php echo $this->global_settings['trigger_color']; ?>;
						text-decoration: none;
						<?php
					break;

					case 'underline-dotted':
						?>
						border-bottom: 1px dotted;
						border-color: <?php echo $this->global_settings['trigger_color']; ?>;
						text-decoration: none;
						<?php
					break;

					case 'highlight':
						?>
						background-color: <?php echo $this->global_settings['trigger_color']; ?>;
						text-decoration: none;
						<?php
					break;

				}
				?>
			}

			/* Tooltip styles */
			.tooltipster-default {
				<?php
				if( $this->global_settings['tooltip_corner_style'] == 'square' ) {
				?>
					border-radius: 0px !important;
				<?php } else { ?>
					border-radius: 10px !important;
				<?php } ?>

				border-color: <?php echo $this->global_settings['tooltip_border_color']; ?> !important;
				background: <?php echo $this->global_settings['tooltip_background_color']; ?> !important;
				color: <?php echo $this->global_settings['tooltip_text_color']; ?> !important;
			}
                        
                        .tooltipster-default a,
                        .tooltipster-default .wp-caption-text {
                                color: <?php echo $this->global_settings['tooltip_text_color']; ?> !important;
                        }

			.tooltipster-default a {
				text-decoration: underline;
			}
                        
                        .tooltipster-default .wp-caption-text {
                            text-align: center;
                            
		</style>
	<?php
	}

	/**
	 * Print tooltip init JS to wp_footer.
	 *
	 * @since 1.0.0
	 */
	function print_tooltip_js() {
	?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.act-tooltip').tooltipster({
					contentAsHTML: true,
					interactive: true,
                                        maxWidth: 600
				});
			});
		</script>
	<?php
	}

}
