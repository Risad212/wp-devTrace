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
use DevTrace\Database;

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
        // define constants
        $this->defineConstants();

        // register plugin activation hook
        register_activation_hook( __FILE__, [ self::class, 'activate' ] );

        // uninstall plugin hook
        register_uninstall_hook( __FILE__, [ self::class, 'uninstall' ] );

        // hook run on plugins loaded
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
     * Create table when activate plugin
     * 
     * @return void
     */
    public static function activate(): void {
      Database::createTables();
    }

    /**
     * Delete table when uninstall plugin
     * 
     * @return void
     */
    public function uninstall(): void {
        Database::dropTables();
    }

    /**
     * Boot plugin modules.
     *
     * @return void
     */
    public function init(): void {
        if( is_admin() ){
              ( new Settings() )->register();
         }
    }
}

WpDevTrace::getInstance();
