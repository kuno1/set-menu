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
		add_action( 'wp_update_nav_menu', [ $this, 'save_menu' ], 10, 2 );
		add_action( 'update_option', [ $this, 'flush_sidebar' ] );
	}

	/**
	 * Update nav menu.
	 *
	 * @param int   $menu_id
	 * @param array $menu_data
	 */
	public function save_menu( $menu_id, $menu_data = [] ) {
		$this->flush_menu_cache();
	}

	/**
	 * Option is updated.
	 *
	 * @param string $option
	 */
	public function flush_sidebar( $option ) {
		if ( 'sidebars_widgets' === $option ) {
			// Remove all caches.
			global $wp_registered_sidebars;
			foreach ( $wp_registered_sidebars as $id => $sidebar ) {
				$key = $this->sidebar_cache_key( $id );
				delete_transient( $key );
			}
		}
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
				set_transient( $cache_key, $cache, $this->menu_cache_lifetime() * 60 );
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

	/**
	 * Render dynamic sidebar
	 *
	 * @param string $id
	 * @return bool
	 */
	public function render_sidebar( $id ) {
		if ( ! $this->sidebar_should_be_cached( $id ) ) {
			return dynamic_sidebar( $id );
		}
		$cache_key = $this->sidebar_cache_key( $id );
		$cache     = get_transient( $cache_key );
		if ( false === $cache ) {
			ob_start();
			$return = dynamic_sidebar( $id );
			$cache  = ob_get_contents();
			ob_end_flush();
			if ( $return ) {
				set_transient( $cache_key, $cache, $this->sidebar_cache_lifetime() * 60 );
			}
		} else {
			printf( '<!-- Cached Sidebar: %s-->', esc_html( $id ) );
			$return = true;
		}
		echo $cache;
		return $return;
	}
}
