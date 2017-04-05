<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

class WSL_Test_Components extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_component_core_enabled()
	{
		$this->assertTrue( wpl_is_component_enabled( 'core' ) );
	}

	function test_component_networks_enabled()
	{
		$this->assertTrue( wpl_is_component_enabled( 'networks' ) );
	}

	function test_component_loginwidget_enabled()
	{
		$this->assertTrue( wpl_is_component_enabled( 'login-widget' ) );
	}

	function test_component_bouncer_enabled()
	{
		$this->assertTrue( wpl_is_component_enabled( 'bouncer' ) );
	}
}
