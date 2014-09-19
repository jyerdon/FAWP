<?php

/**
 * qfa_options_css
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */ 
function qfa_options_css() 
{
    wp_enqueue_style('font-awesome-wordpress-jyp-pluginpage', QFA_CDN);  //enqueue our CSS
}


/**
 * qfa_fetch_versioning_info
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_fetch_versioning_info()
{
	$versioning = wp_remote_get(QFA_VERIFICATION_URL);					//fetch version info
	$body = explode("@", $versioning['body']);							//separate the main body content
	
	$versiondata = explode("-", $body[1]);								//version data
	$descriptiondata = explode("-", $body[2]);							//description data
	
	$version['version'] = end($versiondata);							//fetch the version number
	$version['description'] = end($descriptiondata);					//fetch the version description
	
	return $version;
}

/**
 * qfa_enqueue_fa_css
 * @version 1.0
 * @since 1.0
 * @author jyerdon
 *
 */
function qfa_enqueue_fa_css()
{
	$options = get_option('qfa_settings');
	
	if($options['qfa_enable_cdn'] === 'TRUE' || empty($options['qfa_enable_cdn'])) {	//is the option currently set to TRUE (not true bool)
		wp_enqueue_style('font-awesome-wordpress-jyp', QFA_CDN); } 		//if so, enqueue our CSS
		
	return;
}

/**
 * qfa_set_true
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_set_true()
{
	return 'TRUE';														//quick and dirty return TRUE function
}


/**
 * qfa_parse_icon
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_parse_icon($atts) 
{
	$options = get_option('qfa_settings');
	
	if($options['qfa_enable_cdn'] === 'TRUE')
	{
		extract(shortcode_atts(
			array(
				'id' => 'wordpress',
				'size' => 'DEFAULT',
				'class' => 'DEFAULT'
			), $atts));
			
		if($class !== 'DEFAULT') {											//check to see if the color is default value
			$class = st_string('s:alphanumeric-c:locase', $class); }			//if it isn't, cleanse and locase the string
		else {																//if it is default
			$class = ''; }														//set the class var to 'empty'
	
		if($size !== 'DEFAULT') {											//check to see if the size is default value
			$size = 'fa-'.st_string('s:alphanumerichyphen-c:locase', $size); }	//set to cleansed string
		else {																//if it is default
			$size = '';	}														//set the size 
		
		if($id !== 'DEFAULT') {												//check to see if the id is default value
			$id = 'fa-'.st_string('s:alphanumerichyphen-c:locase', $id); }		//if it isn't, cleanse and locase the string
		else {																//if it is default
			$id = 'fa-wordpress';	}											//set the id 
	
		$complete = '<i class="fa '.$id.' '.$size.' '.$class.'"></i>';		//assemble complete string
	}
	else {
		$complete = ''; }
		
	return $complete;
}


/**
 * qfa_open_iconsheet
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_open_iconsheet()
{
	$options = get_option('qfa_settings');									//fetch option
	
	if($options['qfa_enable_cdn'] === 'TRUE')
	{
		echo '<a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" class="button" target="_blank"> 
				<i class="dashicons dashicons-art" style="margin-top:3px!important;"></i>
				FA Icon Sheet</a>';
	}
}
