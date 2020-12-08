<?php

namespace Codemanas\VczApi\Datastore;

/**
 * Datastore For Post Types
 *
 * @since   3.7.0
 * @author  Deepen Bajracharya
 */
class Options {

	/**
	 * Field slug
	 *
	 * @var string
	 */
	private static $fields_slug = '_vczapi_';

	/**
	 * Get option fields data
	 *
	 * @param $key
	 *
	 * @return bool|mixed|void
	 */
	public static function get_option( $key ) {
		$result = get_option( self::$fields_slug . $key );
		if ( empty( $result ) ) {
			return false;
		}

		return $result;
	}

	/**
	 * Set Options
	 *
	 * @param $key
	 * @param $value
	 */
	public static function set_option( $key, $value ) {
		if ( is_array( $key ) ) {
			foreach ( $key as $k => $v ) {
				update_option( self::$fields_slug . $k, $v );
			}
		} else {
			update_option( self::$fields_slug . $key, $value );
		}
	}

	/**
	 * Delete option by key
	 *
	 * @param $key
	 */
	public static function delete_option( $key ) {
		delete_option( self::$fields_slug . $key );
	}

}