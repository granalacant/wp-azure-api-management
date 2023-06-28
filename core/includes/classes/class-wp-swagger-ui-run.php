<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class is used to bring your plugin to life. 
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 * 
 * Within the add_hooks() function, you can register all of 
 * your WordPress related actions and filters as followed:
 * 
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 * 
 * Once added, you can create the callback function, within this class, as followed: 
 * 
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 * 
 * 
 * HELPER COMMENT END
 */

/**
 * Class Wp_Swagger_Ui_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		WPSGUI
 * @subpackage	Classes/Wp_Swagger_Ui_Run
 * @author		Spanrig Technologies
 * @since		1.0.0
 */
class Wp_Swagger_Ui_Run{

	/**
	 * Our Wp_Swagger_Ui_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'plugin_action_links_' . WPSGUI_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts_and_styles' ), 20 );

		add_action( 'init', array( $this, 'register_swagger_ui' ));
		add_filter('manage_swagger_ui_posts_columns', array( $this, 'wpsgui_embed_column' ));
		add_action('manage_swagger_ui_posts_custom_column', array( $this, 'wpsgui_embed_column_data' ), 10, 2);
		add_filter('upload_mimes', array( $this, 'wpsgui_add_json_yaml_mime_types' ));
		add_action('add_meta_boxes_swagger_ui', array( $this, 'add_swagger_ui_meta_box' ));
		add_action('add_meta_boxes_swagger_ui', array( $this, 'add_swagger_ui_meta_box_shortcode' ));
		add_action('save_post_swagger_ui', array( $this, 'save_swagger_ui_meta_box' ));
		
		add_shortcode('wpsgui', array( $this, 'wpsgui_shortcode'));
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	* Adds action links to the plugin list table
	*
	* @access	public
	* @since	1.0.0
	*
	* @param	array	$links An array of plugin action links.
	*
	* @return	array	An array of plugin action links.
	*/
	public function add_plugin_action_link( $links ) {

		$links['our_shop'] = sprintf( '<a href="%s" target="_blank title="Donate us" style="font-weight:700;">%s</a>', 'https://rzp.io/l/hncvj', __( 'Donate us', 'wp-swagger-ui' ) );

		return $links;
	}


	/**
	 * Enqueue the frontend related scripts and styles for this plugin.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_frontend_scripts_and_styles() {
		wp_enqueue_style( 'wpsgui-swagger-ui-css', WPSGUI_PLUGIN_URL . 'core/includes/assets/css/swagger-ui.css', array(), WPSGUI_VERSION, 'all' );
		wp_enqueue_script( 'wpsgui-swagger-ui-js', WPSGUI_PLUGIN_URL . 'core/includes/assets/js/swagger-ui.js', array(), WPSGUI_VERSION, 'all' );
		wp_enqueue_script( 'wpsgui-swagger-ui-bundle', WPSGUI_PLUGIN_URL . 'core/includes/assets/js/swagger-ui-bundle.js', array(), WPSGUI_VERSION, true );
		wp_enqueue_script( 'wpsgui-swagger-ui-standalone-preset', WPSGUI_PLUGIN_URL . 'core/includes/assets/js/swagger-ui-standalone-preset.js', array('wpsgui-swagger-ui-bundle'), WPSGUI_VERSION, true );
		wp_enqueue_script( 'custom-js', 'https://get.cdnpkg.com/flat-ui/2.1.3/jquery-ui-1.10.3.custom.min.js', array( 'jquery' ),'',true);
	}

	public function register_swagger_ui() {

		/**
		 * Post Type: Swaggers.
		 */
	
		$labels = [
			"name" => esc_html__( "Swagger UI Integration", "custom-post-type-ui" ),
			"singular_name" => esc_html__( "Swagger", "custom-post-type-ui" ),
			"menu_name" => esc_html__( "Swagger UI Integration", "custom-post-type-ui" ),
			"all_items" => esc_html__( "All Swaggers", "custom-post-type-ui" ),
			"add_new" => esc_html__( "Add new", "custom-post-type-ui" ),
			"add_new_item" => esc_html__( "Add new Swagger", "custom-post-type-ui" ),
			"edit_item" => esc_html__( "Edit Swagger", "custom-post-type-ui" ),
			"new_item" => esc_html__( "New Swagger", "custom-post-type-ui" ),
			"view_item" => esc_html__( "View Swagger", "custom-post-type-ui" ),
			"view_items" => esc_html__( "View Swaggers", "custom-post-type-ui" ),
			"search_items" => esc_html__( "Search Swaggers", "custom-post-type-ui" ),
			"not_found" => esc_html__( "No Swaggers found", "custom-post-type-ui" ),
			"not_found_in_trash" => esc_html__( "No Swaggers found in trash", "custom-post-type-ui" ),
			"parent" => esc_html__( "Parent Swagger:", "custom-post-type-ui" ),
			"featured_image" => esc_html__( "Featured image for this Swagger", "custom-post-type-ui" ),
			"set_featured_image" => esc_html__( "Set featured image for this Swagger", "custom-post-type-ui" ),
			"remove_featured_image" => esc_html__( "Remove featured image for this Swagger", "custom-post-type-ui" ),
			"use_featured_image" => esc_html__( "Use as featured image for this Swagger", "custom-post-type-ui" ),
			"archives" => esc_html__( "Swagger archives", "custom-post-type-ui" ),
			"insert_into_item" => esc_html__( "Insert into Swagger", "custom-post-type-ui" ),
			"uploaded_to_this_item" => esc_html__( "Upload to this Swagger", "custom-post-type-ui" ),
			"filter_items_list" => esc_html__( "Filter Swaggers list", "custom-post-type-ui" ),
			"items_list_navigation" => esc_html__( "Swaggers list navigation", "custom-post-type-ui" ),
			"items_list" => esc_html__( "Swaggers list", "custom-post-type-ui" ),
			"attributes" => esc_html__( "Swaggers attributes", "custom-post-type-ui" ),
			"name_admin_bar" => esc_html__( "Swagger", "custom-post-type-ui" ),
			"item_published" => esc_html__( "Swagger published", "custom-post-type-ui" ),
			"item_published_privately" => esc_html__( "Swagger published privately.", "custom-post-type-ui" ),
			"item_reverted_to_draft" => esc_html__( "Swagger reverted to draft.", "custom-post-type-ui" ),
			"item_scheduled" => esc_html__( "Swagger scheduled", "custom-post-type-ui" ),
			"item_updated" => esc_html__( "Swagger updated.", "custom-post-type-ui" ),
			"parent_item_colon" => esc_html__( "Parent Swagger:", "custom-post-type-ui" ),
		];
	
		$args = [
			"label" => esc_html__( "Swaggers", "custom-post-type-ui" ),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => false,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"rest_namespace" => "wp/v2",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => true,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"can_export" => true,
			"rewrite" => [ "slug" => "swagger_ui", "with_front" => true ],
			"query_var" => true,
			"menu_icon" => WPSGUI_PLUGIN_URL."/core/includes/assets/images/swagger-ui-icon.svg",
			"supports" => [ "title" ],
			"show_in_graphql" => false,
		];
	
		register_post_type( "swagger_ui", $args );
	}	


	// Add custom column to our CPT
	public function wpsgui_embed_column($columns) {
   		$columns['embed_code'] = 'Embed Code';
    	return $columns;
	}

	// Display data in the "embed_code" column
	public function wpsgui_embed_column_data($column, $post_id) {
		if($column === 'embed_code') {
			$swagger_ui_json_yaml = get_post_meta($post_id, 'swagger_ui_json_yaml', true);
			if (!empty($swagger_ui_json_yaml)) {
				echo "<strong>[wpsgui id=".esc_attr($post_id)."]</strong>";
			} else {
				echo "<strong style='color: red;'>File is missing!</strong>";
			}
		}
	}

	// Add meta box to swagger_ui CPT
	public function add_swagger_ui_meta_box_shortcode() {
		add_meta_box(
			'swagger_ui_shortcode',
			'Embed code',
			array( $this, 'render_swagger_ui_embed_code_meta_box'),
			'swagger_ui',
			'side',
			'high'
		);
	}

	public function render_swagger_ui_embed_code_meta_box($post) {
		$swagger_ui_json_yaml = get_post_meta($post->ID, 'swagger_ui_json_yaml', true);
		if (!empty($swagger_ui_json_yaml)) {
			echo "<strong>[wpsgui id=".esc_attr($post->ID)."]</strong>";
		} else {
			echo "<strong style='color: red;'>Embed code can't be generated. Please upload a file to generate Embed code.</strong>";
		}
	}

	// Add meta box to swagger_ui CPT
	public function add_swagger_ui_meta_box() {
		add_meta_box(
			'swagger_ui_json_yaml',
			'JSON/YAML',
			array( $this, 'render_swagger_ui_json_yaml_meta_box'),
			'swagger_ui',
			'advanced',
			'high'
		);
	}
	

	// Render meta box HTML
	public function render_swagger_ui_json_yaml_meta_box($post) {
		wp_nonce_field(basename(__FILE__), 'swagger_ui_json_yaml_nonce');
	
		// Get current value of uploaded file
		$swagger_ui_json_yaml = get_post_meta($post->ID, 'swagger_ui_json_yaml', true);
	
		// Get uploaded file name
		$uploaded_file_name = '';
		if (!empty($swagger_ui_json_yaml)) {
			$uploaded_file_name = basename(get_attached_file($swagger_ui_json_yaml));
		}
	
		// Output HTML for file upload field
		echo '<p><label for="swagger_ui_json_yaml_field">Select or upload JSON/YAML file:</label><br>';
		echo '<input type="hidden" id="swagger_ui_json_yaml_field" name="swagger_ui_json_yaml_field" value="' . esc_attr($swagger_ui_json_yaml) . '">';
		echo '<button class="button" id="swagger_ui_json_yaml_upload_button">Select File</button>';
		echo '<br><em>(Only .json or .yaml files are allowed)</em></p>';

		if (!empty($uploaded_file_name)) {
			echo '<strong><em>Current file: ' . esc_html($uploaded_file_name) . '</em></strong>';
		}
	
		// Enqueue media library scripts
		wp_enqueue_media();
		?>
		<script>
		jQuery(document).ready(function($) {
			var file_frame;

			$('#swagger_ui_json_yaml_upload_button').on('click', function(event) {
				event.preventDefault();

				// If the media frame already exists, reopen it.
				if (file_frame) {
					file_frame.open();
					return;
				}

				// Create a new media frame
				file_frame = wp.media({
					title: 'Select JSON/YAML File',
					button: {
						text: 'Use this file'
					},
					multiple: false,
					// library: {
					// 	type: ['application/json', 'text/yaml']
					// }
				});

				// When an image is selected, run a callback.
				file_frame.on('select', function() {
					var attachment = file_frame.state().get('selection').first().toJSON();

					$('#swagger_ui_json_yaml_field').val(attachment.id);
				});

				// Finally, open the media frame
				file_frame.open();
			});
		});
		</script>
		<?php
	}

	// Save meta box data when post is saved
	public function save_swagger_ui_meta_box($post_id) {
	
		// Check if our nonce is set.
		if (!isset($_POST['swagger_ui_json_yaml_nonce'])) {
			return;
		}
	
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['swagger_ui_json_yaml_nonce'], basename(__FILE__))) {
			return;
		}
	
		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
	
		// Check the user's permissions.
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
	
		// Sanitize user input.
		$file_id = sanitize_text_field($_POST['swagger_ui_json_yaml_field']);
	
		// Update the meta field in the database.
		update_post_meta($post_id, 'swagger_ui_json_yaml', $file_id);
	}

	/**
	 * Add support for JSON and YAML files to WordPress media library
	 */
	public function wpsgui_add_json_yaml_mime_types($mimes) {
		$mimes['json'] = 'application/json';
		$mimes['yaml'] = 'text/yaml';
		$mimes['yml'] = 'text/yaml';
		return $mimes;
	}

	public function wpsgui_shortcode($atts){

		// Extract the link argument from the shortcode
		extract( shortcode_atts( array(
			'url' => '',
			'id' => ''
		), $atts ) );

		// Return empty string when bot hare empty.
		if (empty($id) && empty($url)){
			return '';
		}

		$unique_id = "live_".uniqid();
		$swagger_ui_json_yaml = '';
		$output = '';

		if (!empty($id)) {
			$unique_id = 'stored_'.$id;
			$swagger_ui_json_yaml = get_post_meta($id, 'swagger_ui_json_yaml', true);
			if (!empty($swagger_ui_json_yaml)) {
				$url = wp_get_attachment_url($swagger_ui_json_yaml);
			}
		}

		if (!empty($url)) {
			// Generate a unique ID for the Swagger UI container
			$container_id = 'wp-swagger-ui-container-' . $unique_id;
			
			// Output the HTML for the Swagger UI container and script tag
			$output .= '<div id="' . $container_id . '"></div>';

			$output .= '<script>
			jQuery(document).ready( function($) {
				window.' . $unique_id . ' = SwaggerUIBundle({
					url: "' . $url . '",
					dom_id: "#' . $container_id . '",
					deepLinking: true,
					presets: [
					  SwaggerUIBundle.presets.apis,
					  //SwaggerUIStandalonePreset
				  	],
					plugins: [
						SwaggerUIBundle.plugins.DownloadUrl
					],
					//layout: "StandaloneLayout"
			  	});

				window.' . $unique_id . '.load();
				
			});
			</script>';
		}

		return $output;
		
	}
	

}
