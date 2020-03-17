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
	public static function render( $args = [] ) {
		return CacheController::get_instance()->render_menu( $args );
	}
}
