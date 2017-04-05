<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_components_gallery()
{
	return; // ya men 3ach

	// HOOKABLE: 
	do_action( "wpl_component_components_gallery_start" ); 

	$response = wp_remote_get( 'http://miled.github.io/wordpress-pixelpin-login/components-' . wpl_get_version() . '.json', array( 'timeout' => 15, 'sslverify' => false ) );

	if ( ! is_wp_error( $response ) )
	{
		$response = wp_remote_retrieve_body( $response );

		$components = json_decode ( $response );

		if( $components )
		{
?> 
<br />

<h2><?php _wpl_e( "Other Components available", 'wordpress-pixelpin-login' ) ?></h2>

<p><?php _wpl_e( "These components and add-ons can extend the functionality of WordPress PixelPin Login", 'wordpress-pixelpin-login' ) ?>.</p>

<?php
	foreach( $components as $item )
	{
		$item = (array) $item;
		?>
			<div class="wpl_component_div">
				<h3 style="margin:0px;"><?php _wpl_e( $item['name'], 'wordpress-pixelpin-login' ) ?></h3>
				
				<div class="wpl_component_about_div">
					<p>
						<?php _wpl_e( $item['description'], 'wordpress-pixelpin-login' ) ?>
						<br />
						<?php echo sprintf( _wpl__( '<em>By <a href="%s">%s</a></em>' , 'wordpress-pixelpin-login' ), $item['developer_link'], $item['developer_name'] ); ?>
					</p>
				</div>

				<a class="button button-secondary" href="<?php echo $item['download_link']; ?>" target="_blank"><?php _wpl_e( "Get this Component", 'wordpress-pixelpin-login' ) ?></a> 
			</div>	
		<?php
	}
?> 

<div class="wpl_component_div">
	<h3 style="margin:0px;"><?php _wpl_e( "Build yours", 'wordpress-pixelpin-login' ) ?></h3>

	<div class="wpl_component_about_div">
		<p><?php _wpl_e( "Want to build your own custom <b>WordPress PixelPin Login</b> component? It's pretty easy. Just refer to the online developer documentation.", 'wordpress-pixelpin-login' ) ?></p>
	</div>

	<a class="button button-primary"   href="http://miled.github.io/wordpress-pixelpin-login/documentation.html" target="_blank"><?php _wpl_e( "WSL Developer API", 'wordpress-pixelpin-login' ) ?></a> 
	<a class="button button-secondary" href="http://miled.github.io/wordpress-pixelpin-login/submit-component.html" target="_blank"><?php _wpl_e( "Submit your WSL Component", 'wordpress-pixelpin-login' ) ?></a> 
</div>

<?php
		}
	}

	// HOOKABLE: 
	do_action( "wpl_component_components_gallery_end" );
}

// --------------------------------------------------------------------	
