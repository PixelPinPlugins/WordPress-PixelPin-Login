<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* PixelPin networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_networks()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_start" );

	include "wsl.components.networks.setup.php";
	include "wsl.components.networks.sidebar.php"; 

	wsl_admin_welcome_panel();
?>

<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group' ); ?>

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td> 
					<div id="post-body-content">
						<?php
							wsl_component_networks_setup();
						?>
						<a name="wslsettings"></a> 
					</div>
				</td>
				<td width="10"></td>
				<td width="400">
					<?php
						wsl_component_networks_sidebar();
					?>
				</td>
			</tr>
		</table> 
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_networks_end" );
}

wsl_component_networks();

// --------------------------------------------------------------------	
