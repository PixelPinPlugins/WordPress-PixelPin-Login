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

function wsl_component_networks_sidebar()
{
	// HOOKABLE:
	do_action( "wsl_component_networks_sidebar_start" );

	$sections = array(
		'what_is_this'   => 'wsl_component_networks_sidebar_what_is_this',
		'add_more_idps'  => 'wsl_component_networks_sidebar_add_more_idps',
		'basic_insights' => 'wsl_component_networks_sidebar_basic_insights',
	);

	$sections = apply_filters( 'wsl_component_networks_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_networks_sidebar_sections', $action );
	}

	// HOOKABLE:
	do_action( 'wsl_component_networks_sidebar_sections' );
}

// --------------------------------------------------------------------

function wsl_component_networks_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Welcome to WordPress PixelPin Login", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p style="padding:0;margin:12px 0;">
				<?php _wsl_e('<b>WordPress PixelPin Login</b> is a PixelPin implementation of <b>WordPress Social Login</b>. PixelPin Login authenticates and authorises using the OpenID Connect protocol.', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:12px 0;">
				<?php _wsl_e('<b>WordPress PixelPin Login</b> allows your website visitors and customers to register on using their existing PixelPin account, eliminating the need to fill out registration forms and remember usernames and passwords', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:0 0 12px;">
				<?php _wsl_e('<b>WordPress PixelPin Login</b> come with a number of useful <b><a href="options-general.php?page=wordpress-pixelpin-login&wslp=components">Components</a></b> or add-ons that can be essential for your needs', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:0 0 12px;">
				<?php _wsl_e('If you are still new to things, we recommend that you read the <b><a href="http://miled.github.io/wordpress-pixelpin-login/documentation.html" target="_blank">WSL Documentation</a></b> and to make sure your server meet the minimum system requirements by running <b><a href="http://hybridauth.com/hawp4/wp-admin/options-general.php?page=wordpress-pixelpin-login&wslp=tools">WSL Diagnostics</a></b>', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:0 0 12px;">
				<?php _wsl_e('If you run into any issue, then refer to <b><a href="http://miled.github.io/wordpress-pixelpin-login/support.html" target="_blank">Help &amp; Support</a></b>', 'wordpress-pixelpin-login') ?>.
			</p>
		</div>
	</div>
</div>
<?php
}

add_action( 'wsl_component_networks_sidebar_what_is_this', 'wsl_component_networks_sidebar_what_is_this' );

// --------------------------------------------------------------------

function wsl_component_networks_sidebar_add_more_idps()
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/32x32/icondock/';
?>

<div class="postbox" id="ppenable">
	<div class="inside">
		<h3><?php _wsl_e("Enable PixelPin", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p style="padding:0;margin:0 0 12px;">
				<?php _wsl_e('<b>Just Click</b> on the PixelPin logo to enable PixelPin', 'wordpress-social-login') ?>.
			</p>

			<div style="width: 320px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
				<?php
					$nb_used = count( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG );

					foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item )
					{
						$provider_id   = isset( $item["provider_id"]   ) ? $item["provider_id"]   : '';
						$provider_name = isset( $item["provider_name"] ) ? $item["provider_name"] : '';

						if( isset( $item["default_network"] ) && $item["default_network"] )
						{
							continue;
						}

						if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) )
						{
							?>
								<a href="options-general.php?page=wordpress-pixelpin-login&wslp=networks&enable=<?php echo $provider_id ?>#setup<?php echo strtolower( $provider_id ) ?>"><img src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" /></a>
							<?php

							$nb_used--;
						}
					}

					if( $nb_used == count( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG ) )
					{
						_wsl_e("PixelPin is Enabled", 'wordpress-pixelpin-login');
					}
					else
					{

					}
				?>
			</div>
		</div>
	</div>
</div>

<?php
}

add_action( 'wsl_component_networks_sidebar_add_more_idps', 'wsl_component_networks_sidebar_add_more_idps' );

// --------------------------------------------------------------------

function wsl_component_networks_sidebar_basic_insights()
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/32x32/icondock/';
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Insights", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px">
			<?php
				$total_users     = wsl_get_wordpess_users_count();
				$total_users_wsl = wsl_get_wsl_users_count();

				if( $total_users && $total_users_wsl )
				{
					$users_conversion = ( 100 * $total_users_wsl ) / $total_users;
					?>
						<!-- Insights - conversions -->
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("Conversions", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<tr>
								<td width="60%"><?php _wsl_e("WP users", 'wordpress-pixelpin-login') ?></td><td><?php echo $total_users; ?></td>
							</tr>
							<tr>
								<td><?php _wsl_e("WSL users", 'wordpress-pixelpin-login') ?></td><td><?php echo $total_users_wsl; ?></td>
							</tr>
							<tr>
								<td><?php _wsl_e("Conversions", 'wordpress-pixelpin-login') ?></td><td style="border-top:1px solid #ccc">+<b><?php echo number_format($users_conversion, 2, '.', ''); ?></b> %</td>
							</tr>
						</table>

						<!-- Insights by provider -->
						<?php
							$data = wsl_get_stored_hybridauth_user_profiles_count_by_field( 'provider' );
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By provider", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<?php
								$total_profiles_wsl = 0;

								foreach( $data as $item ){
								?>
									<tr>
										<td width="60%">
											<img src="<?php echo $assets_base_url . strtolower( $item->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php _wsl_e($item->provider, 'wordpress-pixelpin-login') ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
									$total_profiles_wsl += (int) $item->items;
								}
							?>
							<tr>
								<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo $total_profiles_wsl; ?></b> <?php _wsl_e("WSL profiles", 'wordpress-pixelpin-login') ?></td>
							</tr>
							<tr>
								<td align="right">&nbsp;</td><td><b><?php echo $total_users_wsl; ?></b> <?php _wsl_e("WSL users", 'wordpress-pixelpin-login') ?></td>
							</tr>
						</table>

						<!-- Insights by gender -->
						<?php
							$data = wsl_get_stored_hybridauth_user_profiles_count_by_field( 'gender' );
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By gender", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<?php
								foreach( $data as $item ){
									if( ! $item->gender ) $item->gender = "Unknown";
								?>
									<tr>
										<td width="60%">
											<?php echo ucfirst( $item->gender ); ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?>
						</table>

						<!-- Insights by age -->
						<?php
							$data = wsl_get_stored_hybridauth_user_profiles_count_by_field( 'age' );
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By age", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<?php
								foreach( $data as $item ){
									if( ! $item->age ) $item->age = "Unknown";
								?>
									<tr>
										<td width="60%">
											<?php echo ucfirst( $item->age ); ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?>
						</table>
					<?php
				}
				else
				{
					?>
						<p>
							<?php _wsl_e("There's no data yet", 'wordpress-pixelpin-login') ?>.
						</p>
					<?php
				}
			?>
		</div>
	</div>
</div>
<?php
}

add_action( 'wsl_component_networks_sidebar_basic_insights', 'wsl_component_networks_sidebar_basic_insights' );

// --------------------------------------------------------------------
