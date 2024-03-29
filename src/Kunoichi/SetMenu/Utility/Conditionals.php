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
	protected function menu_cache_lifetime() {
		/**
		 * Filter the default value of menu cache lifetime.
		 *
		 * @param int $lifetime Minutes. Default value is 60.
		 */
		$default_menu_lifetime = (int) apply_filters( 'set_menu_default_menu_lifetime', 60 );
		return max( 1, (int) get_option( 'menu-lifetime', $default_menu_lifetime ) );
	}

	/**
	 * Detect if sidebar should be cached.
	 *
	 * @param string $sidebar_id
	 * @return bool
	 */
	protected function sidebar_should_be_cached( $sidebar_id ) {
		$sidebar_ids = array_filter( (array) get_option( 'set-menu-widgets-to-cache', [] ) );
		return in_array( $sidebar_id, $sidebar_ids );
	}

	/**
	 * Sidebar lifetime.
	 *
	 * @return int
	 */
	protected function sidebar_cache_lifetime() {
		/**
		 * Filter the default value of sidebar cache lifetime.
		 *
		 * @param int $lifetime Minutes. Default value is 60.
		 */
		$default_sidebar_lifetime = (int) apply_filters( 'set_menu_default_sidebar_lifetime', 60 );
		return max( 1, (int) get_option( 'sidebar-lifetime', $default_sidebar_lifetime ) );
	}

	/**
	 * Get sidebar cache key.
	 *
	 * @param string $id
	 * @return string
	 */
	protected function sidebar_cache_key( $id ) {
		return sprintf( 'sidebar_cache_%s', $id );
	}
}
