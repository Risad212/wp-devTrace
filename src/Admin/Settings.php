<?php

namespace DevTrace\Admin;

use DevTrace\Admin\ErrorLog;

class Settings {

    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'admin_menu', [ $this, 'addMenu' ] );
        add_action( 'admin_init', [ $this, 'registerSettings' ] );
        add_action( 'admin_notices', [ $this, 'productionWarning' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAssets' ] );
    }

    /**
     * Add admin menu.
     *
     * @return void
     */
    public function addMenu(): void {
        add_menu_page(
            __( 'WP DevTrace', 'wp-devtrace' ),
            __( 'DevTrace', 'wp-devtrace' ),
            'manage_options',
            'wp-devtrace',
            [ $this, 'renderPage' ],
            'dashicons-warning',
            80
        );

        add_submenu_page(
            'wp-devtrace',
            __( 'Error Logs', 'wp-devtrace' ),
            __( 'Error Logs', 'wp-devtrace' ),
            'manage_options',
            'wp-devtrace-errors',
            [ new ErrorLog(), 'render' ],
        );
    }

    /**
     * Register settings.
     *
     * @return void
     */
    public function registerSettings(): void {
        register_setting( 'devtrace_settings', 'devtrace_active' );
    }

    /**
     * Show production warning.
     *
     * @return void
     */
    public function productionWarning(): void {
        $screen = get_current_screen();

        if ( $screen->id !== 'toplevel_page_wp-devtrace' ) {
            return;
        }
        ?>
        <div class="notice notice-warning">
            <p>
                <strong><?php echo esc_html__( '⚠ WP DevTrace Warning:', 'wp-devtrace' ); ?></strong>
                <?php echo esc_html__( 'Do not enable on production site. Development use only.', 'wp-devtrace' ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Enqueue assets.
     *
     * @param string $hook
     * @return void
     */
    public function enqueueAssets( string $hook ): void {

        wp_enqueue_style(
            'devtrace',
            DEVTRACE_URL . '/assets/css/devtrace.css',
            [],
            DEVTRACE_VERSION
        );

        wp_enqueue_script(
            'devtrace',
            DEVTRACE_URL . '/assets/js/devtrace.js',
            ['jquery'],
            DEVTRACE_VERSION,
            true
        );
    }

    /**
     * Render settings page.
     *
     * @return void
     */
    public function renderPage(): void {
        $isActive = get_option( 'devtrace_active', false );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'WP DevTrace Settings', 'wp-devtrace' ); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'devtrace_settings' ); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <?php echo esc_html__( 'Enable DevTrace', 'wp-devtrace' ); ?>
                        </th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    name="devtrace_active"
                                    value="1"
                                    <?php checked( 1, $isActive ); ?>
                                />
                                <?php echo esc_html__( 'Enable error tracking and debugging', 'wp-devtrace' ); ?>
                            </label>
                            <p class="description">
                                <?php echo esc_html__( 'Only enable on development environments.', 'wp-devtrace' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button( esc_html__( 'Save Settings', 'wp-devtrace' ) ); ?>
            </form>
        </div>
        <?php
    }
}

