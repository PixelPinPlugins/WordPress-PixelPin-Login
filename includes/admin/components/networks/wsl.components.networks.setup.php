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

/**
* This should be reworked somehow.. the code has become spaghettis
*/
function wsl_component_networks_setup()
{
	// HOOKABLE:
	do_action( "wsl_component_networks_setup_start" );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/16x16/';
	$assets_setup_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/setup/';

	// save settings?
	if( isset( $_REQUEST["enable"] ) && $_REQUEST["enable"] )
	{
		$provider_id = $_REQUEST["enable"];

		update_option( 'wsl_settings_' . $provider_id . '_enabled', 1 );
	}
?>
<script>
	function toggleproviderkeys(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return;
		}

		if(jQuery('#wsl_settings_' + idp + '_enabled').val()==1)
		{
			jQuery('.wsl_tr_settings_' + idp).show();
			JQuery('#ppenable').hide();
		}
		else
		{
			jQuery('.wsl_tr_settings_' + idp).hide();
			jQuery('.wsl_div_settings_help_' + idp).hide();
		}

		return false;
	}

	function toggleproviderhelp(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		jQuery('.wsl_div_settings_help_' + idp).toggle();

		return false;
	}
</script>
<?php
	foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item ):
		$provider_id                = isset( $item["provider_id"]       ) ? $item["provider_id"]       : '';
		$provider_name              = isset( $item["provider_name"]     ) ? $item["provider_name"]     : '';

		$require_client_id          = isset( $item["require_client_id"] ) ? $item["require_client_id"] : '';
		$require_api_key            = isset( $item["require_api_key"]   ) ? $item["require_api_key"]   : '';
		$default_api_scope          = isset( $item["default_api_scope"] ) ? $item["default_api_scope"] : '';
		$provide_email              = isset( $item["provide_email"]     ) ? $item["provide_email"]     : '';

		$provider_new_app_link      = isset( $item["new_app_link"]      ) ? $item["new_app_link"]      : '';
		$provider_userguide_section = isset( $item["userguide_section"] ) ? $item["userguide_section"] : '';

		$provider_callback_url      = "" ;

		if( ! ( ( isset( $item["default_network"] ) && $item["default_network"] ) || get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) )
		{
			continue;
		}

		// default endpoint_url
		$endpoint_url = WORDPRESS_PIXELPIN_LOGIN_HYBRIDAUTH_ENDPOINT_URL;

		if( isset( $item["callback"] ) && $item["callback"] )
		{
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . '?hauth.done=' . $provider_id . '</span>';
		}

		if( isset( $item["custom_callback"] ) && $item["custom_callback"] )
		{
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . 'endpoints/' . strtolower( $provider_id ) . '.php</span>';
		}

		$setupsteps = 0;
?>
		<a name="setup<?php echo strtolower( $provider_id ) ?>"></a>
		<div class="stuffbox" id="namediv">
			<h3>
				<label class="wp-neworks-label">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" /> <?php _wsl_e( $provider_name, 'wordpress-pixelpin-login' ) ?>
				</label>
			</h3>
			<div class="inside">
				<table class="form-table editcomment">
					<tbody>
						<tr>
							<td style="width:125px"><?php _wsl_e("Enabled", 'wordpress-pixelpin-login') ?>:</td>
							<td>
								<select
									name="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>"
									id="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>"
									onChange="toggleproviderkeys('<?php echo $provider_id; ?>')"
								>
									<option value="1" <?php if(   get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> ><?php _wsl_e("Yes", 'wordpress-pixelpin-login') ?></option>
									<option value="0" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> ><?php _wsl_e("No", 'wordpress-pixelpin-login') ?></option>
								</select>
							</td>
							<td style="width:160px">&nbsp;</td>
						</tr>

						<?php if ( $provider_new_app_link ){ ?>
							<?php if ( $require_client_id ){ // key or id ? ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Client ID", 'wordpress-pixelpin-login') ?>:</td>
									<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_id' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_id' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-pixelpin-login') ?></a></td>
								</tr>
							<?php } else { ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Client Key", 'wordpress-pixelpin-login') ?>:</td>
									<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_key' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_key' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-pixelpin-login') ?></a></td>
								</tr>
							<?php }; ?>

							<?php if( ! $require_api_key ) { ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Client Secret", 'wordpress-pixelpin-login') ?>:</td>
									<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_secret' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_secret' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-pixelpin-login') ?></a></td>
								</tr>
							<?php } ?>

							<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Redirect URI", 'wordpress-pixelpin-login') ?>:</td>
									<td><input readonly dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_redirect_uri' ?>" value="<?php echo strip_tags( $provider_callback_url ); ?>" ></td>
								</tr>

							<?php if( get_option( 'wsl_settings_development_mode_enabled' ) ) { ?>
								<?php if( $default_api_scope ) { ?>
									<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
										<td><?php _wsl_e("Application Scope", 'wordpress-pixelpin-login') ?>:</td>
										<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_scope' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_scope' ); ?>" ></td>
									</tr>
								<?php } ?>

								<?php if( $provider_callback_url ) { ?>
									<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
										<td><?php _wsl_e("Callback URL", 'wordpress-pixelpin-login') ?>:</td>
										<td><input dir="ltr" type="text" name="" value="<?php echo  strip_tags( $provider_callback_url ); ?>" readonly="readonly"></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } // if require registration ?>
					</tbody>
				</table>

				<?php if ( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) : ?>
					<?php if (  $provider_id == "Steam" ) : ?>
						<div class="fade updated">
							<p>
								<b><?php _wsl_e("Notes", 'wordpress-pixelpin-login') ?>:</b>
							</p>
							<p>
								      1. <?php echo sprintf( _wsl__("<b>%s</b> do not require an external application, however if the Web API Key is provided, then WSL will be able to get more information about the connected %s users", 'wordpress-pixelpin-login'), $provider_name , $provider_name ) ?>.
								<br />2. <?php echo sprintf( _wsl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-pixelpin-login'), $provider_name ) ?>.

								<?php _wsl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-pixelpin-login&wslp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-pixelpin-login') ?>.
							</p>
						</div>
					<?php elseif ( $provider_new_app_link && strlen( trim( get_option( 'wsl_settings_' . $provider_id . '_app_secret' ) ) ) == 0 ) : ?>
						<div class="fade error">
							<p>
								<?php echo sprintf( _wsl__('<b>%s</b> requires that you create an external application linking your website to their API. To know how to create this application, click on &ldquo;Where do I get this info?&rdquo; and follow the steps', 'wordpress-pixelpin-login'), $provider_name, $provider_name ) ?>.
							</p>
						</div>
					<?php elseif ( in_array( $provider_id, array( "Twitter", "Identica", "Tumblr", "Goodreads", "500px", "Vkontakte", "Gowalla", "Steam" ) ) ) : ?>
						<div class="fade updated">
							<p>
								<b><?php _wsl_e("Note", 'wordpress-pixelpin-login') ?>:</b>

								<?php echo sprintf( _wsl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-pixelpin-login'), $provider_name ) ?>.

								<?php _wsl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-pixelpin-login&wslp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-pixelpin-login') ?>.
							</p>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<br />
				<div
					class="wsl_div_settings_help_<?php echo $provider_id; ?>"
					style="<?php if( isset( $_REQUEST["enable"] ) && ! isset( $_REQUEST["settings-updated"] ) && $_REQUEST["enable"] == $provider_id ) echo "-"; // <= lolz ?>display:none;"
				>
					<hr class="wsl" />
					<?php if (  $provider_id == "Steam" ) : ?>
					<?php elseif ( $provider_new_app_link  ) : ?>
						<?php _wsl_e('<span style="color:#CB4B16;">Application</span> id and secret (also sometimes referred as <span style="color:#CB4B16;">Consumer</span> key and secret or <span style="color:#CB4B16;">Client</span> id and secret) are what we call an application credentials', 'wordpress-pixelpin-login') ?>.

						<?php echo sprintf( _wsl__( 'This application will link your website <code>%s</code> to <code>%s API</code> and these credentials are needed in order for <b>%s</b> users to access your website', 'wordpress-pixelpin-login'), $_SERVER["SERVER_NAME"], $provider_name, $provider_name ) ?>.
						<br />

						<?php _wsl_e("These credentials may also differ in format, name and content depending on the pixelpin network.", 'wordpress-pixelpin-login') ?>
						<br />
						<br />

						<?php echo sprintf( _wsl__('To enable authentication with this provider and to register a new <b>%s API Application</b>, follow the steps', 'wordpress-pixelpin-login'), $provider_name ) ?>
						:<br />
					<?php else: ?>
							<p><?php echo sprintf( _wsl__('<b>Done.</b> Nothing more required for <b>%s</b>', 'wordpress-pixelpin-login'), $provider_name) ?>.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
<?php
	endforeach;
?>
	<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-pixelpin-login') ?>" />
<?php
	// HOOKABLE:
	do_action( "wsl_component_networks_setup_end" );
}


// --------------------------------------------------------------------
