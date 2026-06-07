<?php
/**
 * Plugin Name: WP DevTrace
 * Plugin URI:
 * Description: Developer debugging toolkit. Pretty error pages, crash logger, query profiler and plugin conflict tester.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            hmrisad
 * Author URI:
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-devtrace
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use DevTrace\Admin\Settings;

final class WpDevTrace {

    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Class constructor.
     *
     * @access private
     */
    private function __construct() {
        $this->defineConstants();
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
     * Initialize singleton instance.
     *
     * @return WpDevTrace
     */
    public static function getInstance(): WpDevTrace {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define plugin constants.
     *
     * @return void
     */
    private function defineConstants(): void {
        define( 'DEVTRACE_VERSION', self::VERSION );
        define( 'DEVTRACE_FILE',    __FILE__ );
        define( 'DEVTRACE_PATH',    __DIR__ );
        define( 'DEVTRACE_URL',     plugins_url( '', __FILE__ ) );
    }

    /**
     * Boot plugin modules.
     *
     * @return void
     */
    public function init(): void {
       ( new Settings() )->register();
    }
}

WpDevTrace::getInstance();
