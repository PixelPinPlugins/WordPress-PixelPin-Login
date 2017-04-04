<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup()
{
	$sections = array(
	);

	$sections = apply_filters( 'wsl_component_buddypress_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_contacts_settings_setup_sections', $action );
	}	
?>
<div>
	<?php
		// HOOKABLE: 
		do_action( 'wsl_component_contacts_settings_setup_sections' );
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-pixelpin-login') ?>" /> 
	</div>
</div>
<?php
}


