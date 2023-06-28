<?php
/**
 * Embed Swagger UI
 *
 * @package       WPSGUI
 * @author        Spanrig Technologies
 * @license       gplv3-or-later
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Embed Swagger UI
 * Plugin URI:    https://spanrig.com
 * Description:   A clean plugin to embed Swagger UI anywhere on your WP website.
 * Version:       1.0.0
 * Author:        Spanrig Technologies
 * Author URI:    https://www.upwork.com/fl/hncvj
 * Text Domain:   wp-swagger-ui
 * Domain Path:   /languages
 * License:       GPLv3 or later
 * License URI:   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Embed Swagger UI. If not, see <https://www.gnu.org/licenses/gpl-3.0.html/>.
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
 * The function WPSGUI() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'WPSGUI_NAME',			'Embed Swagger UI' );

// Plugin version
define( 'WPSGUI_VERSION',		'1.0.0' );

// Plugin Root File
define( 'WPSGUI_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'WPSGUI_PLUGIN_BASE',	plugin_basename( WPSGUI_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'WPSGUI_PLUGIN_DIR',	plugin_dir_path( WPSGUI_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'WPSGUI_PLUGIN_URL',	plugin_dir_url( WPSGUI_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once WPSGUI_PLUGIN_DIR . 'core/class-wp-swagger-ui.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Spanrig Technologies
 * @since   1.0.0
 * @return  object|Wp_Swagger_Ui
 */
function WPSGUI() {
	return Wp_Swagger_Ui::instance();
}

WPSGUI();
