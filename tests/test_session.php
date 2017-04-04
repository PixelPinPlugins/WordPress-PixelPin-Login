<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

class WSL_Test_Session extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_wsl_version()
	{
		global $WORDPRESS_PIXELPIN_LOGIN_VERSION;

		$this->assertEquals( $_SESSION["wsl::plugin"], "WordPress PixelPin Login " . $WORDPRESS_PIXELPIN_LOGIN_VERSION );
	}
}
