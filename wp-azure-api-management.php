<?php
/**
 * WP Azure API Management
 *
 * @package       WPAPIM
 * @author        granalacant
 * @license       apache-2.0
 * @version       0.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   WP Azure API Management
 * Plugin URI:    https://github.com/granalacant/wp-azure-api-management
 * Description:   A client for Azure API Management to manage APIs and expose them to WordPress.
 * Version:       0.0.1
 * Author:        granalacant
 * Author URI:    https://github.com/granalacant
 * Text Domain:   wp-azure-api-management
 * Domain Path:   /languages
 * License:       Apache-2.0
 * License URI:   apache.org/licenses/LICENSE-2.0
 *
 * You should have received a copy of the Apache License 2.0
 * along with this program. If not, see <https://www.apache.org/licenses/LICENSE-2.0>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function WPAPIM() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'WPAPIM_NAME',			'WP Azure API Management' );

// Plugin version
define( 'WPAPIM_VERSION',		'0.0.1' );

// Plugin Root File
define( 'WPAPIM_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'WPAPIM_PLUGIN_BASE',	plugin_basename( WPAPIM_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'WPAPIM_PLUGIN_DIR',	plugin_dir_path( WPAPIM_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'WPAPIM_PLUGIN_URL',	plugin_dir_url( WPAPIM_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once WPAPIM_PLUGIN_DIR . 'core/class-wp-azure-api-management.php';


/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  granalacant
 * @since   0.0.1
 * @return  object|Wp_Azure_Api_Management
 */
function WPAPIM() {
	return Wp_Azure_Api_Management::instance();
}

WPAPIM();
