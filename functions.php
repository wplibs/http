<?php

use WPLibs\Http\Request;
use WPLibs\Http\Json_Response;
use WPLibs\Http\Redirect_Response;

if ( defined( 'WPLIBS_HTTP_VERSION' ) ) {
	return;
}

/* Constants */
define( 'WPLIBS_HTTP_VERSION', '1.0.3' );

// Setup the Http request on `plugins_loaded`.
if ( ! did_action( 'plugins_loaded' ) ) {
	add_action( 'plugins_loaded', 'wplibs_http_request', 0 );
} else {
	wplibs_http_request();
}

/**
 * Returns the Http request instance.
 *
 * @return \WPLibs\Http\Request
 */
function wplibs_http_request() {
	if ( empty( $GLOBALS['wplibs_http'] ) ) {
		$GLOBALS['wplibs_http'] = Request::capture();
	}

	return $GLOBALS['wplibs_http'];
}

/**
 * Creates a json response.
 *
 * @param  mixed $data    The response data.
 * @param  int   $status  The response status code.
 * @param  array $headers An array of response headers.
 * @param  int   $options The options passed to json_encode() function.
 * @return \WPLibs\Http\Json_Response
 */
function wplibs_response_json( $data = null, $status = 200, $headers = [], $options = 0 ) {
	return new Json_Response( $data, $status, $headers, $options );
}

/**
 * Creates a redirect response.
 *
 * @param  string $url           The URL to redirect to.
 * @param  int    $status        The status code (302 by default).
 * @param  array  $headers       The headers (Location is always set to the given URL).
 * @param  bool   $safe_redirect Use safe redirect or not.
 * @return \WPLibs\Http\Redirect_Response
 */
function wplibs_redirect( $url, $status = 302, $headers = [], $safe_redirect = false ) {
	$response = new Redirect_Response( $url, $status, $headers, $safe_redirect );

	$response->set_request( wplibs_http_request() );

	if ( function_exists( 'wplibs_session' ) ) {
		$response->set_session( wplibs_session()->get_store() );
	}

	return $response;
}

/**
 * Creates a safe redirect response.
 *
 * @param  string $url     The URL to redirect to.
 * @param  int    $status  The status code (302 by default).
 * @param  array  $headers The headers (Location is always set to the given URL).
 * @return \WPLibs\Http\Redirect_Response
 */
function wplibs_safe_redirect( $url, $status = 302, $headers = [] ) {
	return wplibs_redirect( $url, $status, $headers, true );
}

/**
 * Create a new redirect response to the previous location.
 *
 * @param  mixed $fallback The fallback, if null it'll be admin_url() or home_url() depend by the context.
 * @param  int   $status   The response status code.
 * @param  array $headers  The response headers.
 * @return \WPLibs\Http\Redirect_Response
 */
function wplibs_redirect_back( $fallback = null, $status = 302, $headers = [] ) {
	$previous = wp_get_referer();

	if ( ! $previous && ! $fallback ) {
		$fallback = is_admin() ? admin_url() : home_url();
	}

	return wplibs_safe_redirect( $previous ?: $fallback, $status, $headers );
}
