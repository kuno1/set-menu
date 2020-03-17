<?php
namespace Kunoichi\SetMenu;


use Hametuha\SingletonPattern\Singleton;
use Kunoichi\SetMenu\Utility\Conditionals;

/**
 * Cache Controller
 *
 * @package set-menu
 */
class CacheController extends Singleton {

	use Conditionals;

	protected function init() {

	}

	/**
	 * Do nav menu with cached.
	 *
	 * @param array $args
	 * @return string|void|false
	 */
	public function render_menu( $args = [] ) {
		$location = isset( $args['theme_location'] ) ? $args['theme_location'] : '';
		if ( ! $location ) {
			return wp_nav_menu( $args );
		}
		if ( $this->is_ignore_location( $location ) ) {
			return wp_nav_menu( $args );
		}
		// Try to cache;
		$cache_key = $this->get_menu_cache_key( $location );
		$cache     = get_transient( $cache_key );
		$echo      = ! ( isset( $args['echo'] ) && !$args['echo'] );
		if ( false === $cache ) {
			$cache = wp_nav_menu( array_merge( $args, [
				'echo' => false,
			] ) );
			if ( $cache ) {
				set_transient( $cache_key, $cache, $this->menu_cache_lifetime() );
			}
		} else {
			printf( '<!-- Cached Menu: %s -->', esc_html( $location ) );
		}
		if ( $echo ) {
			echo $cache;
		} else {
			return $cache;
		}
	}
}
