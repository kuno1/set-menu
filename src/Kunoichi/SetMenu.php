<?php

namespace Kunoichi;

use Kunoichi\SetMenu\AdminDisplay;
use Kunoichi\SetMenu\CacheController;

/**
 * Set Menu
 *
 * @package set-menu
 */
final class SetMenu {

	private static $instance = null;

	/**
	 * SetMenu constructor.
	 */
	final private function __construct() {
		AdminDisplay::get_instance();
		CacheController::get_instance();
		$mo = dirname( dirname( __DIR__ ) ) . '/languages/set-menu-' . get_locale() . '.mo';
		if ( file_exists( $mo ) ) {
			load_textdomain( 'set-menu', $mo );
		}
	}

	/**
	 * Enable set menu
	 */
	public static function enable() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
	}

	/**
	 * Equivalent to wp_nav_menu but cached.
	 *
	 * @param array $args
	 * @return string|false|void
	 */
	public static function nav_menu( $args = [] ) {
		return CacheController::get_instance()->render_menu( $args );
	}

	/**
	 * Equivalent to dynamic_sidebar but cached.
	 *
	 * @param string $id
	 * @return bool
	 */
	public static function sidebar( $id ) {
		return CacheController::get_instance()->render_sidebar( $id );
	}
}
