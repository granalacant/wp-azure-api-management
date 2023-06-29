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
 * Class Wp_Azure_Api_Management_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		WPAPIM
 * @subpackage	Classes/Wp_Azure_Api_Management_Run
 * @author		granalacant
 * @since		0.0.1
 */
class Wp_Azure_Api_Management_Run{

	/**
	 * Our Wp_Azure_Api_Management_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 0.0.1
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
	 * @since	0.0.1
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts_and_styles' ), 20 );

		add_action( 'init', array( $this, 'register_azure_api_management' ));
		add_filter('manage_azure_api_management_posts_columns', array( $this, 'WPAPIM_embed_column' ));
		add_action('manage_azure_api_management_posts_custom_column', array( $this, 'WPAPIM_embed_column_data' ), 10, 2);
		add_filter('upload_mimes', array( $this, 'WPAPIM_add_json_yaml_mime_types' ));
		add_action('add_meta_boxes_azure_api_management', array( $this, 'add_azure_api_management_meta_box' ));
		add_action('add_meta_boxes_azure_api_management', array( $this, 'add_azure_api_management_meta_box_shortcode' ));
		add_action('add_meta_boxes_azure_api_management', array( $this, 'add_azure_api_management_meta_box_connection' ));
		add_action('save_post_azure_api_management', array( $this, 'save_azure_api_management_meta_box' ));
		
		add_shortcode('WPAPIM', array( $this, 'WPAPIM_shortcode'));
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Enqueue the frontend related scripts and styles for this plugin.
	 *
	 * @access	public
	 * @since	0.0.1
	 *
	 * @return	void
	 */
	public function enqueue_frontend_scripts_and_styles() {
		wp_enqueue_style( 'WPAPIM-swagger-ui-css', WPAPIM_PLUGIN_URL . 'core/includes/assets/css/swagger-ui.css', array(), WPAPIM_VERSION, 'all' );
		wp_enqueue_script( 'WPAPIM-swagger-ui-js', WPAPIM_PLUGIN_URL . 'core/includes/assets/js/swagger-ui.js', array(), WPAPIM_VERSION, 'all' );
		wp_enqueue_script( 'WPAPIM-swagger-ui-bundle', WPAPIM_PLUGIN_URL . 'core/includes/assets/js/swagger-ui-bundle.js', array(), WPAPIM_VERSION, true );
		wp_enqueue_script( 'WPAPIM-swagger-ui-standalone-preset', WPAPIM_PLUGIN_URL . 'core/includes/assets/js/swagger-ui-standalone-preset.js', array('WPAPIM-swagger-ui-bundle'), WPAPIM_VERSION, true );
		wp_enqueue_script( 'custom-js', 'https://get.cdnpkg.com/flat-ui/2.1.3/jquery-ui-1.10.3.custom.min.js', array( 'jquery' ),'',true);
	}

	public function register_azure_api_management() {

		/**
		 * Post Type: Swaggers.
		 */
	
		$labels = [
			"name" => esc_html__( "Azure API Management", "custom-post-type-ui" ),
			"singular_name" => esc_html__( "API", "custom-post-type-ui" ),
			"menu_name" => esc_html__( "Azure API Management", "custom-post-type-ui" ),
			"all_items" => esc_html__( "All APIs", "custom-post-type-ui" ),
			"add_new" => esc_html__( "Add new", "custom-post-type-ui" ),
			"add_new_item" => esc_html__( "Add new API", "custom-post-type-ui" ),
			"edit_item" => esc_html__( "Edit API", "custom-post-type-ui" ),
			"new_item" => esc_html__( "New API", "custom-post-type-ui" ),
			"view_item" => esc_html__( "View API", "custom-post-type-ui" ),
			"view_items" => esc_html__( "View APIs", "custom-post-type-ui" ),
			"search_items" => esc_html__( "Search APIs", "custom-post-type-ui" ),
			"not_found" => esc_html__( "No APIs found", "custom-post-type-ui" ),
			"not_found_in_trash" => esc_html__( "No APIs found in trash", "custom-post-type-ui" ),
			"parent" => esc_html__( "Parent API:", "custom-post-type-ui" ),
			"featured_image" => esc_html__( "Featured image for this API", "custom-post-type-ui" ),
			"set_featured_image" => esc_html__( "Set featured image for this API", "custom-post-type-ui" ),
			"remove_featured_image" => esc_html__( "Remove featured image for this API", "custom-post-type-ui" ),
			"use_featured_image" => esc_html__( "Use as featured image for this API", "custom-post-type-ui" ),
			"archives" => esc_html__( "APIs archives", "custom-post-type-ui" ),
			"insert_into_item" => esc_html__( "Insert into API", "custom-post-type-ui" ),
			"uploaded_to_this_item" => esc_html__( "Upload to this API", "custom-post-type-ui" ),
			"filter_items_list" => esc_html__( "Filter APIs list", "custom-post-type-ui" ),
			"items_list_navigation" => esc_html__( "APIs list navigation", "custom-post-type-ui" ),
			"items_list" => esc_html__( "APIs list", "custom-post-type-ui" ),
			"attributes" => esc_html__( "APIs attributes", "custom-post-type-ui" ),
			"name_admin_bar" => esc_html__( "Swagger", "custom-post-type-ui" ),
			"item_published" => esc_html__( "API published", "custom-post-type-ui" ),
			"item_published_privately" => esc_html__( "API published privately.", "custom-post-type-ui" ),
			"item_reverted_to_draft" => esc_html__( "API reverted to draft.", "custom-post-type-ui" ),
			"item_scheduled" => esc_html__( "API scheduled", "custom-post-type-ui" ),
			"item_updated" => esc_html__( "API updated.", "custom-post-type-ui" ),
			"parent_item_colon" => esc_html__( "Parent API:", "custom-post-type-ui" ),
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
			"rewrite" => [ "slug" => "azure_api_management", "with_front" => true ],
			"query_var" => true,
			"menu_icon" => WPAPIM_PLUGIN_URL."/core/includes/assets/images/azure-api-management-icon.svg",
			"supports" => [ "title" ],
			"show_in_graphql" => false,
		];
	
		register_post_type( "azure_api_management", $args );
	}	


	// Add custom column to our CPT
	public function WPAPIM_embed_column($columns) {
   		$columns['embed_code'] = 'Embed Code';
    	return $columns;
	}

	// Display data in the "embed_code" column
	public function WPAPIM_embed_column_data($column, $post_id) {
		if($column === 'embed_code') {
			$azure_api_management_json_yaml = get_post_meta($post_id, 'azure_api_management_json_yaml', true);
			if (!empty($azure_api_management_json_yaml)) {
				echo "<strong>[WPAPIM id=".esc_attr($post_id)."]</strong>";
			} else {
				echo "<strong style='color: red;'>File is missing!</strong>";
			}
		}
	}

	// Add meta box to azure_api_management CPT
	public function add_azure_api_management_meta_box_shortcode() {
		add_meta_box(
			'azure_api_management_shortcode',
			'Embed code',
			array( $this, 'render_azure_api_management_embed_code_meta_box'),
			'azure_api_management',
			'side',
			'high'
		);
	}

	public function render_azure_api_management_embed_code_meta_box($post) {
		$azure_api_management_json_yaml = get_post_meta($post->ID, 'azure_api_management_json_yaml', true);
		if (!empty($azure_api_management_json_yaml)) {
			echo "<strong>[WPAPIM id=".esc_attr($post->ID)."]</strong>";
		} else {
			echo "<strong style='color: red;'>Embed code can't be generated. Please upload a file to generate Embed code.</strong>";
		}
	}

	// Add meta box to azure_api_management connection
	public function add_azure_api_management_meta_box_connection() {
		add_meta_box(
			'azure_api_management_connection',
			'Azure Connection',
			array( $this, 'render_azure_api_management_connection_meta_box'),
			'azure_api_management',
			'advanced',
			'high'
		);
	}

	// Render meta box HTML
	public function render_azure_api_management_connection_meta_box($post) {
		// Input HTML for Azure connection
		echo '<table class="wp-list-table widefat fixed striped pages">';
		echo '<thead>';
		echo '<th>Configuration field</th>';
		echo '<th>Value</th>';
		echo '<th>Actions</th>';
		echo '</thead>';
		echo '<tbody id="the-list">';
		echo '<tr>';
			echo '<td>Access Token</td>';
			echo '<td>Value</td>';
			echo '<td><a id="setAccessToken" class="page-title-action">Set new value</a> <a data-id="accesstoken" id="deleteAccessToken" class="page-title-action">Delete</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Subscription Id</td>';
			echo '<td>Value</td>';
			echo '<td><a id="setSubscriptionId" class="page-title-action">Set new value</a> <a data-id="subscriptionid" id="deleteSubscriptionId" class="page-title-action">Delete</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Resource Group</td>';
			echo '<td>Value</td>';
			echo '<td><a id="setResourceGroup" class="page-title-action">Set new value</a> <a data-id="resourcegroup" id="deleteResourceGroup" class="page-title-action">Delete</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Service Name</td>';
			echo '<td>Value</td>';
			echo '<td><a id="setServiceId" class="page-title-action">Set new value</a> <a data-id="serviceid" id="deleteServiceId" class="page-title-action">Delete</a></td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</br>';
		echo '<a action-id="" name="" id="testapim" class="btn btn-primary">Test Azure API Management configuration</a> <div id="responseTestAPIM"></div>';
	}

	// Add meta box to azure_api_management CPT
	public function add_azure_api_management_meta_box() {
		add_meta_box(
			'azure_api_management_json_yaml',
			'JSON/YAML',
			array( $this, 'render_azure_api_management_json_yaml_meta_box'),
			'azure_api_management',
			'advanced',
			'high'
		);
	}

	// Render meta box HTML
	public function render_azure_api_management_json_yaml_meta_box($post) {
		wp_nonce_field(basename(__FILE__), 'azure_api_management_json_yaml_nonce');
	
		// Get current value of uploaded file
		$azure_api_management_json_yaml = get_post_meta($post->ID, 'azure_api_management_json_yaml', true);
	
		// Get uploaded file name
		$uploaded_file_name = '';
		if (!empty($azure_api_management_json_yaml)) {
			$uploaded_file_name = basename(get_attached_file($azure_api_management_json_yaml));
		}
	
		// Output HTML for file upload field
		echo '<p><label for="azure_api_management_json_yaml_field">Select or upload JSON/YAML file:</label><br>';
		echo '<input type="hidden" id="azure_api_management_json_yaml_field" name="azure_api_management_json_yaml_field" value="' . esc_attr($azure_api_management_json_yaml) . '">';
		echo '<button class="button" id="azure_api_management_json_yaml_upload_button">Select File</button>';
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

			$('#azure_api_management_json_yaml_upload_button').on('click', function(event) {
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

					$('#azure_api_management_json_yaml_field').val(attachment.id);
				});

				// Finally, open the media frame
				file_frame.open();
			});
		});
		</script>
		<?php
	}

	
	// Save meta box data when post is saved
	public function save_azure_api_management_meta_box($post_id) {
	
		// Check if our nonce is set.
		if (!isset($_POST['azure_api_management_json_yaml_nonce'])) {
			return;
		}
	
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['azure_api_management_json_yaml_nonce'], basename(__FILE__))) {
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
		$file_id = sanitize_text_field($_POST['azure_api_management_json_yaml_field']);
	
		// Update the meta field in the database.
		update_post_meta($post_id, 'azure_api_management_json_yaml', $file_id);
	}

	/**
	 * Add support for JSON and YAML files to WordPress media library
	 */
	public function WPAPIM_add_json_yaml_mime_types($mimes) {
		$mimes['json'] = 'application/json';
		$mimes['yaml'] = 'text/yaml';
		$mimes['yml'] = 'text/yaml';
		return $mimes;
	}

	public function WPAPIM_shortcode($atts){

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
		$azure_api_management_json_yaml = '';
		$output = '';

		if (!empty($id)) {
			$unique_id = 'stored_'.$id;
			$azure_api_management_json_yaml = get_post_meta($id, 'azure_api_management_json_yaml', true);
			if (!empty($azure_api_management_json_yaml)) {
				$url = wp_get_attachment_url($azure_api_management_json_yaml);
			}
		}

		if (!empty($url)) {
			// Generate a unique ID for the Swagger UI container
			$container_id = 'wp-azure-api-management-container-' . $unique_id;
			
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
