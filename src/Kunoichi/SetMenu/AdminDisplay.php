<?php
namespace Kunoichi\SetMenu;

use Hametuha\SingletonPattern\Singleton;
use Kunoichi\SetMenu\Utility\Conditionals;

/**
 * Customize admin screen.
 *
 * @package
 */
class AdminDisplay extends Singleton {

	use Conditionals;

	/**
	 * Constructor.
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_setting' ] );
	}

	/**
	 * Register setting options.
	 */
	public function register_setting() {
		add_settings_section(
			'set-menu-locations',
			__( 'Navigation Menu Caching', 'set-menu' ),
			function() {
			    printf( '<p class="description">%s</p>', esc_html__( 'Menu cache will be purged when you update navigation menus.', 'set-menu' ) );
            },
			'set-menu-setting'
		);

		add_settings_field(
			'locations-to-ignore',
		    __( 'Dynamic Locations', 'set-menu' ),
			function() {
			    $locations = get_registered_nav_menus();
			    if ( empty( $locations ) ) : ?>
			        <p class="description">
                        <?php esc_html_e( 'This theme has no specified navigation menu.', 'set-menu' );?>
                    </p>
                <?php else : ?>
                    <p>
                        <?php foreach ( $locations as $location => $label ) : ?>
                        <label style="display: inline-block; margin: 0 20px 10px 0;">
                            <input name="locations-to-ignore[]" type="checkbox" value="<?php echo esc_attr( $location ) ?>" <?php checked( in_array( $location, $this->get_dynamic_menus() ) ) ?> />
                            <?php echo esc_html( $label ) ?>
                        </label>
                        <?php endforeach; ?>
                    </p>
                    <p class="description">
						<?php esc_html_e( 'If checked, navigation menus in that location never be not cached and always display dynamic contents.', 'set-menu' );?>
						<?php esc_html_e( 'If you have dynamic elements like EC cart or login status in your navigation menu, check it.', 'set-menu' );?>
                    </p>
				<?php endif;
            },
            'set-menu-setting',
			'set-menu-locations'
		);

		add_settings_field(
			'menu-lifetime',
			__( 'Cache Lifetime', 'set-menu' ),
			function() {
				?>
                <input type="number" class="regular-text" value="<?php echo esc_attr( $this->menu_cache_lifetime() ) ?>" name="menu-lifetime" id="menu-lifetime" />
                <p class="description">
                    <?php esc_html_e( 'Cache life time in minutes.', 'set-menu' ) ?>
                </p>
				<?php
			},
			'set-menu-setting',
			'set-menu-locations'
		);

		register_setting( 'set-menu-setting', 'locations-to-ignore' );
		register_setting( 'set-menu-setting', 'menu-lifetime' );


		add_settings_section(
			'set-menu-widgets',
			__( 'Widgets Caching', 'set-menu' ),
			function() {
				printf( '<p class="description">%s</p>', esc_html__( 'You can explicitly cache widgets in sidebars', 'set-menu' ) );
			},
			'set-menu-setting'
		);

		add_settings_field(
			'set-menu-widgets-to-cache',
			__( 'Cached Sidebars', 'set-menu' ),
			function() {
			    global $wp_registered_sidebars;
				if ( empty( $wp_registered_sidebars ) ) : ?>
                    <p class="description">
						<?php esc_html_e( 'This theme has no sidebars', 'set-menu' );?>
                    </p>
				<?php else : ?>
                    <p>
						<?php foreach ( $wp_registered_sidebars as $id => $sidebar ) : ?>
                            <label style="display: inline-block; margin: 0 20px 10px 0;">
                                <input name="set-menu-widgets-to-cache[]" type="checkbox" value="<?php echo esc_attr( $id ) ?>" <?php checked( $this->sidebar_should_be_cached( $id ) ) ?> />
								<?php echo esc_html( $sidebar['name'] ) ?>
                            </label>
						<?php endforeach; ?>
                    </p>
                    <p class="description">
						<?php esc_html_e( 'If whole sidebar will be cached. Do not check if one contains dynamic widget(e.g. Cart, login profile)', 'set-menu' );?>
                    </p>
				<?php endif;
			},
			'set-menu-setting',
			'set-menu-widgets'
		);

		add_settings_field(
			'sidebar-lifetime',
			__( 'Cache Lifetime', 'set-menu' ),
			function() {
				?>
                <input type="number" class="regular-text" value="<?php echo esc_attr( $this->sidebar_cache_lifetime() ) ?>" name="sidebar-lifetime" id="sidebar-lifetime" />
                <p class="description">
					<?php esc_html_e( 'Cache life time in minutes.', 'set-menu' ) ?>
                </p>
				<?php
			},
			'set-menu-setting',
			'set-menu-widgets'
		);

		register_setting( 'set-menu-setting', 'set-menu-widgets-to-cache' );
		register_setting( 'set-menu-setting', 'sidebar-lifetime' );
	}

	/**
	 * Add menu page.
	 */
	public function admin_menu() {
		$title = __( 'Theme Cache Setting', 'set-menu' );
		add_theme_page( __( 'Cache Setting', 'set-menu' ), $title, 'manage_options', 'set-menu-setting', function() use ( $title ) {
			?>
			<div class="wrap">
				<h1><?php echo esc_html( $title ) ?></h1>
				<form method="POST" action="options.php">
					<?php
					settings_fields( 'set-menu-setting' );
					do_settings_sections( 'set-menu-setting' );
					submit_button();
					?>
				</form>
			</div>
			<?php
		} );
	}
}
