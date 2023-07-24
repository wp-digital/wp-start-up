# WPStartUp: Simplifying Initial WordPress Setup

### Description

WPStartUp is a powerful tool that streamlines the initial setup process after installing WordPress. It enables you to effortlessly create projects in Bugsnag and Pingdom, enhancing your website's performance and monitoring capabilities.

### Installation

There are two convenient ways to install WPStartUp:

1. **Using Composer:**
   The recommended method is to use [Composer](https://getcomposer.org/). Simply run the following command:

   ```bash
   composer require innocode-digital/wp-start-up
   ```

   WPStartUp will be installed as a [Must Use Plugin](https://codex.wordpress.org/Must_Use_Plugins) by default. If you wish to customize the installation path, you can control it using `extra.installer-paths` in your `composer.json`.

2. **Using Git Clone:**
   Alternatively, you can clone the repository directly into the `wp-content/mu-plugins/` or `wp-content/plugins/` directory. Follow these steps:

   ```bash
   cd wp-content/plugins/
   git clone git@github.com:wp-digital/wp-start-up.git
   cd wp-start-up/
   composer install
   ```

If you installed the plugin as a regular plugin, activate **WPStartUp** from the Plugins page in your WordPress dashboard or use [WP-CLI](https://make.wordpress.org/cli/handbook/): `wp plugin activate wp-start-up`.

### Configuration

To configure WPStartUp, add the following constants to your `wp-config.php` file:

```php
define( 'BUGSNAG_TOKEN', '' );
define( 'BUGSNAG_PROJECT', '' );

define( 'PINGDOM_TOKEN', '' );
define( 'PINGDOM_PROJECT', '' );
```

Please note that defining the `BUGSNAG_TOKEN` constant means the Bugsnag project is already created, and the plugin will not create a new one.

### Usage

WPStartUp automatically creates projects in Bugsnag and Pingdom, boosting your website's performance monitoring capabilities. Should you wish to extend its functionality with new integrations, use the `wp_start_up_integrations` hook:

```php
add_filter( 'wp_start_up_integrations', function( array $integrations ): array {
    $integrations[] = new YourCustomIntegration();

    return $integrations;
} );
```

Please ensure that your integration implements the `WPD\WPStartUp\Interfaces\IntegrationInterface` interface.

By default, WPStartUp stores plugin settings in the WordPress options table. If you prefer a custom storage solution, use the `wp_start_up_default_storage` filter:

```php
add_filter( 'wp_start_up_default_storage', function(): \WPD\WPStartUp\Interfaces\StorageInterface {
    return new YourCustomStorage();
} );
```

Again, ensure that your custom storage implements the `\WPD\WPStartUp\Interfaces\StorageInterface` interface.

Additionally, WPStartUp uses the native `wp_remote_request` function to send API requests. If you want to use a different approach, you can modify this behavior using the `wp_start_up_default_sender` filter:

```php
add_filter( 'wp_start_up_default_sender', function(): \WPD\WPStartUp\Interfaces\SenderInterface {
    return new YourCustomSender();
} );
```

Similarly, your custom sender should implement the `\WPD\WPStartUp\Interfaces\SenderInterface` interface.

With WPStartUp, you have the freedom to tailor your WordPress setup and integrate it seamlessly with other services, ensuring a smooth and efficient website management experience.
