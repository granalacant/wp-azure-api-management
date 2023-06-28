<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Wp_Azure_Api_Management_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Wp_Azure_Api_Management' ) ) :

	/**
	 * Main Wp_Azure_Api_Management Class.
	 *
	 * @package		WPAPIM
	 * @subpackage	Classes/Wp_Azure_Api_Management
	 * @since		0.0.1
	 * @author		granalacant
	 */
	final class Wp_Azure_Api_Management {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	0.0.1
		 * @var		object|Wp_Azure_Api_Management
		 */
		private static $instance;

		/**
		 * WPAPIM helpers object.
		 *
		 * @access	public
		 * @since	0.0.1
		 * @var		object|Wp_Azure_Api_Management_Helpers
		 */
		public $helpers;

		/**
		 * WPAPIM settings object.
		 *
		 * @access	public
		 * @since	0.0.1
		 * @var		object|Wp_Azure_Api_Management_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	0.0.1
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'wp-azure-api-management' ), '0.0.1' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	0.0.1
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'wp-azure-api-management' ), '0.0.1' );
		}

		/**
		 * Main Wp_Azure_Api_Management Instance.
		 *
		 * Insures that only one instance of Wp_Azure_Api_Management exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		0.0.1
		 * @static
		 * @return		object|Wp_Azure_Api_Management	The one true Wp_Azure_Api_Management
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Wp_Azure_Api_Management ) ) {
				self::$instance					= new Wp_Azure_Api_Management;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Wp_Azure_Api_Management_Helpers();
				self::$instance->settings		= new Wp_Azure_Api_Management_Settings();

				//Fire the plugin logic
				new Wp_Azure_Api_Management_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'WPAPIM/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   0.0.1
		 * @return  void
		 */
		private function includes() {
			require_once WPAPIM_PLUGIN_DIR . 'core/includes/classes/class-wp-azure-api-management-helpers.php';
			require_once WPAPIM_PLUGIN_DIR . 'core/includes/classes/class-wp-azure-api-management-settings.php';

			require_once WPAPIM_PLUGIN_DIR . 'core/includes/classes/class-wp-azure-api-management-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   0.0.1
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   0.0.1
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'wp-azure-api-management', FALSE, dirname( plugin_basename( WPAPIM_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.