<?php

namespace Kunoichi\SetMenu\Utility;


trait Conditionals {

	/**
	 * Return ids of no-cache menu.
	 *
	 * @return int[]
	 */
	public function get_dynamic_menus() {
		return array_filter( (array) get_option( 'locations-to-ignore', apply_filters( 'set_menu_default_ignored_locations', [] ) ) );
	}

	/**
	 * Get cache key.
	 *
	 * @param string $location
	 * @return string
	 */
	protected function get_menu_cache_key( $location ) {
		return sprintf( 'menu_cache_%s', $location );
	}

	/**
	 * Flush menu cache
	 */
	public function flush_menu_cache() {
		foreach ( get_registered_nav_menus() as $location => $label ) {
			delete_transient( $this->get_menu_cache_key( $location ) );
		}
	}

	/**
	 * Detect if this location is ignored
	 *
	 * @param string $location
	 * @return bool
	 */
	protected function is_ignore_location( $location ) {
		return in_array( $location, $this->get_dynamic_menus() );
	}

	/**
	 * Get life time of menu cache.
	 *
	 * @return int
	 */
	protected function menu_cache_lifetime () {
		return max( 1, (int) get_option( 'menu-lifetime', 60 ) );
	}

	public function save_location( $theme_location ) {

	}
}
