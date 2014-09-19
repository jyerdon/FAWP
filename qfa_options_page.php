<?php

/**
 * qfa_add_admin_menu
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_add_admin_menu() 
{ 
	add_options_page('Quick Font Awesome', 'Quick Font Awesome', 'manage_options', 'qfa', 'qfa_options_page');
}

/**
 * qfa_settings_exist
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_settings_exist() 
{ 
	if(!get_option('qfa_settings')) { 										//if option does not exist
		add_option('qfa_settings'); }											//create the option
}

/**
 * qfa_settings_init
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_settings_init() 
{ 
	register_setting('qfa_option_page', 'qfa_settings');					//register our settings group

	add_settings_section(													//set up the settings section
		'qfa_option_page_section', 
		__('', 'wordpress'), 
		'qfa_settings_section_callback', 
		'qfa_option_page'
	);

	add_settings_field(														//set up the field
		'qfa_enable_cdn', 
		__('Enable or Disable the Font Awesome library', 'wordpress'), 
		'qfa_enable_cdn_render', 
		'qfa_option_page', 
		'qfa_option_page_section' 
	);


}

/**
 * qfa_enable_cdn_render
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_enable_cdn_render() 
{ 
	$options = get_option('qfa_settings');									//fetch the option
	
	?>
	<select name='qfa_settings[qfa_enable_cdn]'>
		<option value='TRUE' <?php selected($options['qfa_enable_cdn'], ''); ?>>Select Option</option>
		<option value='TRUE' <?php selected($options['qfa_enable_cdn'], 'TRUE'); ?>>Enable Font Awesome</option>
		<option value='FALSE' <?php selected($options['qfa_enable_cdn'], 'FALSE'); ?>>Disable Font Awesome</option>
	</select>

<?php
}

/**
 * qfa_settings_section_callback
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_settings_section_callback() 
{ 
	echo __('', 'wordpress');													//init a content display callback; not used right now
}

/**
 * qfa_options_page
 * @version 1.0
 * @since 2.0
 * @author jyerdon
 *
 */
function qfa_options_page() 
{ 
	$options = get_option('qfa_settings');
	
	$facdntest = $options['qfa_enable_cdn'];									//fetch the CDN enabled setting

	$versioning = qfa_fetch_versioning_info();									//fetch our version information
	
	if(floatval($versioning['version']) > floatval(QFA_VERSION)) 				//compare the version numbers; if available version is higher than installed
	{
		$current = FALSE;															//set the CURRENT value to FALSE (plugin is NOT current)
		$mesg = '<p><h4> Your plugin is out of date!</h4>
				<em>Current Version: '.QFA_VERSION.'<br>
				New Version: '.$versioning['version'].'<br>
				Description: '.$versioning['description'].'</p></em>';				//set our display message
	}
	elseif(floatval($versioning['version']) === floatval(QFA_VERSION))			//is the available version equal to installed version
	{
		$current = TRUE; 															//set the CURRENT value to TRUE (plugin is current)	
		$mesg = 'Your plugin is up to date';
	}
	elseif(floatval($versioning['version']) < floatval(QFA_VERSION)) 			//is the available version somehow older than the installed version
	{
		$current = FALSE;															//set the CURRENT value to FALSE (plugin is not current)
		$mesg = '<p><h4> Your plugin is from the future! </h4>
				<em>Current Version: '.QFA_VERSION.'<br>
				New Version: '.$versioning['version'].'<br>
				Description: You must be a time traveller.</p></em>';			//set our display message
	}
	?>
<div class="wrap">
	<h2>Quick Font Awesome</h2>
	<h3>How to Use Quick Font Awesome (QFA) </h3>
	<hr />
	<p>
		This plugin provides the user with a quick way to incorporate the Font Awesome font icon library into your WP content.<br>
		You can use either a shortcode or straight HTML (as shown on the 
			<a href="http://fortawesome.github.io/Font-Awesome/examples/" target="_blank">Font Awesome Examples page</a>). 
		<h4><strong>EXAMPLE -> shortcode: [fa_icon id="iconname" size="desiredsize" class="yourcustomclass"]</strong></h4>
		You are able to enter up to three pieces of information when you use the shortcode: <br>
		<table class="widefat">
<tbody>
   <tr>
     <td><strong>id</strong></td>
     <td>This is the icon name you want to use, minus the fa- prefix. If you wanted the <strong><i class="fa fa-chain"></i></strong> icon for example,
     	you would use <strong>lock</strong> instead of <strong>fa-lock</strong></td>
   </tr>
   <tr>
     <td><strong>size</strong></td>
     <td>You're able to have your icon be different sizes: large through 5x. To use this, valid values are <strong>lg, 2x, 3x, 4x, 5x</strong></td>
   </tr>
   <tr>
     <td><strong>class</strong></td>
     <td>This is any custom CSS class (that you've provided) you want to have affect the icon</td>
   </tr>
</tbody>
</table>
	</p><br>
	<h3>Plugin Information</h3>
	<hr />
	<table class="widefat">
<thead>
    <tr>
        <th><strong>Installed Plugin Version</strong></th>
        <th><strong>Active Font Awesome Version</strong></th>    
		<th><strong>System Messages</strong></th>
    </tr>
</thead>
<tbody>
   <tr>
     <td><?php echo QFA_VERSION; ?></td>
     <td><?php echo FA_CSS_VERSION; ?></td>
     <td><?php echo $mesg; ?></td>
   </tr>
</tbody>
</table>
<br>

	<h3>Font Awesome Plugin Settings</h3>
	<hr />
</div>
	<form action='options.php' method='post'>
		<?php
		settings_fields('qfa_option_page');
		do_settings_sections('qfa_option_page');
		submit_button();
		?>
		
	</form>
	<br>
	<hr />
	<br>
	<em><strong>Quick Font Awesome</strong> is made by Jake (jyerdon) in 2014. FYI, it uses the <a href="https://github.com/jyerdon/st_toolbox" target="_blank">st_toolbox</a> library (also a Jake thing)</em>   
	<?php

}
