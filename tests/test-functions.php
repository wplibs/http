<?php

require_once dirname( __DIR__ ) . '/functions.php';

class Functions_Test extends WP_UnitTestCase {
	public function testHttpRequest() {
		$request = wplibs_http_request();

		$this->assertInstanceOf( \WPLibs\Http\Request::class, $request );
		$this->assertSame( $request, wplibs_http_request() );
	}
}
