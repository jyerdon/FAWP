<?php
/*
   Plugin Name: 	Quick Font Awesome
   Plugin URI: 		https://github.com/jyerdon/FAWP
   Description: 	Functionality to quickly & easily add Font Awesome to WordPress
   Version: 		2.0
   Author: 			jyerdon  [jyerdon@gmail.com]
   Author URI: 		https://github.com/jyerdon
   License: 		GPL2
*/

/*	INIT BLOCK	*/
require_once dirname(__FILE__).'/qfa_functions.php';				//include base & advanced functions
require_once dirname(__FILE__).'/qfa_options_page.php';				//include options page functionality
require_once dirname(__FILE__).'/st_toolbox_mod.php';				//include string operations toolbox

register_deactivation_hook(__FILE__, 'qfa_deactivation');			//deactivation hook

add_action('wp_head', 'qfa_enqueue_fa_css');						//add CSS enqueue hook
add_action('admin_enqueue_scripts', 'qfa_options_css');				//add the CSS to the options page
add_shortcode('fa_icon', 'qfa_parse_icon');							//add shortcode for shortcode parser
add_action('media_buttons','qfa_open_iconsheet', 11);				//add button for FA icon cheat sheet

add_action('admin_menu', 'qfa_add_admin_menu');						//add the admin menu
add_action('admin_init', 'qfa_settings_init');							//init the settings

if(!defined('QFA_VERSION')) {										//check if version is defined
	define('QFA_VERSION', '2.0'); }										//set the plugin version
	
if(!defined('FA_CSS_VERSION')) {									//check if css version has been defined
	define('FA_CSS_VERSION', '4.2.0'); }									//set the CSS version
	
if(!defined('QFA_CDN')) {											//check if cdn variable has been set
	define('QFA_CDN', '//maxcdn.bootstrapcdn.com/font-awesome/'.FA_CSS_VERSION.'/css/font-awesome.min.css'); }	
	
if(!defined('QFA_VERIFICATION_URL')) {								//cgeck versioning url
	define('QFA_VERIFICATION_URL', 'https://raw.githubusercontent.com/jyerdon/QFA/master/qfa_version'); }



/* 
 * qfa_deactivation
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 * 
*/
function qfa_deactivation()
{
	remove_action('admin_menu', 'qfa_init_options_page');			//connect to hook for addition of plugin menu
	remove_action('wp_head', 'qfa_enqueue_fa_css');					//action to include Font Awesome CSS	remove_action('admin_menu', 'fawp_init_options_page');				//connect to hook for addition of plugin menu
	
	remove_shortcode('fa_icon');									//remove the shortcode
	
	unregister_setting('qfa_option_page', 'qfa_settings');			//remove our setting group	
	delete_option('qfa_settings');	
}
