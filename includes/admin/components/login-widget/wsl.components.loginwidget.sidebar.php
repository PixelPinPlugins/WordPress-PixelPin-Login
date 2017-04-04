<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_loginwidget_sidebar()
{
	$sections = array(
		'what_is_this'        => 'wsl_component_loginwidget_sidebar_what_is_this',
		'auth_widget_preview' => 'wsl_component_loginwidget_sidebar_auth_widget_preview',
		'custom_integration'  => 'wsl_component_loginwidget_sidebar_custom_integration',
	);

	$sections = apply_filters( 'wsl_component_loginwidget_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_loginwidget_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wsl_component_loginwidget_sidebar_sections' );
}

// --------------------------------------------------------------------	

function wsl_component_loginwidget_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("What's This?", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e("On this section you can fully customize <b>WordPress PixelPin Login Widget</b> and define the way you want it to look and behave", 'wordpress-pixelpin-login') ?>.
			</p>

			<p>
				<?php _wsl_e("<b>WordPress PixelPin Login</b> will attempt to display the authentication widget in the default WordPress comments, login and register forms", 'wordpress-pixelpin-login') ?>.
			</p>

			<p>
				<?php _wsl_e('For more information about the WSL Widget, refer to the online userguide <b><a href="http://miled.github.io/wordpress-pixelpin-login/widget.html" target="_blank">Widget Customization</a></b> and <b><a href="http://miled.github.io/wordpress-pixelpin-login/themes.html" target="_blank">Widget Themes</a></b>', 'wordpress-pixelpin-login') ?>. 
			</p>
		</div>
	</div> 
</div> 		
<?php
}

// --------------------------------------------------------------------	

function wsl_component_loginwidget_sidebar_auth_widget_preview()
{
?>
<style>
.wp-pixelpin-login-provider-list { padding: 10px; }
.wp-pixelpin-login-provider-list a {text-decoration: none; }
.wp-pixelpin-login-provider-list img{ border: 0 none; }
</style>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Widget preview", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e("This is a preview of what should be on the comments area", 'wordpress-pixelpin-login') ?>. 
			</p>

			<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
				<?php do_action( 'wordpress_pixelpin_login', array( 'mode' => 'test' ) ); ?> 
			</div> 
		</div>
	</div> 
</div> 		
<?php
}

// --------------------------------------------------------------------	

function wsl_component_loginwidget_sidebar_custom_integration()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Custom integration", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e("If you want to add the widget to another location in your website, you can insert the following code in that location", 'wordpress-pixelpin-login') ?>: 
				<pre dir="ltr" style="width: 380px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;"> &lt;?php do_action( 'wordpress_pixelpin_login' ); ?&gt; </pre> 
				<?php _wsl_e("For posts and pages, you may use this shortcode", 'wordpress-pixelpin-login') ?>:
				<div dir="ltr" style="width: 380px;background-color: #EDEFF4;border:1px solid #6B84B4; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;">[wordpress_pixelpin_login]</div> 
			</p>

			<p>
				<b><?php _wsl_e('Notes', 'wordpress-pixelpin-login') ?>:</b>
				<br />
				1. <?php _wsl_e('WSL Widget will only show up for non connected users', 'wordpress-pixelpin-login') ?>.
				<br />
				2. <?php _wsl_e('In case you are using a caching plugin on your website, you might need to empty the cache for any change to take effect', 'wordpress-pixelpin-login') ?>.
				<br />
				3. <?php _wsl_e('Adblock Plus users with &ldquo;<a href="https://adblockplus.org/en/features#pixelpinmedia" target="_blank">antipixelpin filter</a>&rdquo; enabled may not see the providers icons', 'wordpress-pixelpin-login') ?>.
			</p>

			<p>
				<b><?php _wsl_e('Tip', 'wordpress-pixelpin-login') ?>:</b>
				<br />
				<?php _wsl_e('You can use <a href="http://wordpress.org/extend/plugins/html-javascript-adder/" target="_blank">HTML Javascript Adder</a> plugin in combination with WSL to display the Widget in your website sidebar by using the shortcode [wordpress_pixelpin_login]', 'wordpress-pixelpin-login') ?>.
			</p>
		</div>
	</div> 
</div> 		
<?php
}

// --------------------------------------------------------------------	
