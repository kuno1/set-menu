# Set Menu

Cached menu and widgets for WordPress.

## Installation

Use composer with your theme.

```
composer require kunoichi/set-menu
```

## How to Use

### Step 1: Initialization

Enable in your `functions.php`

```php

reuqire __DIR__ . '/vendor/autoload.php';
Kunoichi\SetMenu::enable();
```

### Step 2: Enable Cached Menu

Replace yoru `wp_nav_menu` to `Kunoich\SetMenu::nav_menu`.
Checking with `has_nav_mehu` is recommended.

```php
if ( has_nav_menu( 'header'  ) ) {
	Kunoichi\SetMenu::nav_menu( [
		'container'      => false,
		'menu_class'     => 'footer-social-menu',
		'depth'          => 1,
		'theme_location' => 'social-menu',
	] );
}
```

### Step 3: Enable Cached Widgets

Replace your `dynamic_sidebar` to `Kunoichi\SetMenu::sidebar`.

```php
<footer class="footer">
	<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
		<div class="container">
			<div class="row" id="footer-widgets">
				<?php Kunoichi\SetMenu::sidebar( 'footer-widgets' ); ?>
			</div>
		</div>
	<?php endif; ?>
</footer>
```

## License

GPL 3.0 or later.
