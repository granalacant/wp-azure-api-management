<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class contains all of the plugin related settings.
 * Everything that is relevant data and used multiple times throughout 
 * the plugin.
 * 
 * To define the actual values, we recommend adding them as shown above
 * within the __construct() function as a class-wide variable. 
 * This variable is then used by the callable functions down below. 
 * These callable functions can be called everywhere within the plugin 
 * as followed using the get_plugin_name() as an example: 
 * 
 * WPAPIM->settings->get_plugin_name();
 * 
 * HELPER COMMENT END
 */

/**
 * Class Wp_Azure_Api_Management_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		WPAPIM
 * @subpackage	Classes/Wp_Azure_Api_Management_Settings
 * @author		granalacant
 * @since		0.0.1
 */
class Wp_Azure_Api_Management_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   0.0.1
	 */
	private $plugin_name;

	/**
	 * Our Wp_Azure_Api_Management_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 0.0.1
	 */
	function __construct(){

		$this->plugin_name = WPAPIM_NAME;
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	0.0.1
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'WPAPIM/settings/get_plugin_name', $this->plugin_name );
	}
}
