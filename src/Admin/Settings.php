<?php

namespace DevTrace\Admin;

class Settings {

    /**
     * Register admin menu.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'admin_menu', [ $this, 'addMenu' ] );
        add_action( 'admin_init', [ $this, 'registerSettings' ] );
        add_action( 'admin_notices', [ $this, 'productionWarning' ] );
    }

    /**
     * Add admin menu page.
     *
     * @return void
     */
    public function addMenu(): void {
        add_menu_page(
            'WP DevTrace',
            'DevTrace',
            'manage_options',
            'wp-devtrace',
            [ $this, 'renderPage' ],
            'dashicons-warning',
            80
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

        echo '<div class="notice notice-warning">
            <p>
                <strong>⚠ WP DevTrace Warning:</strong>
                Please do not enable this plugin on a production site.
                It is intended for development environments only.
                Enabling it on production may expose sensitive error details to users.
            </p>
        </div>';
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
            <h1>WP DevTrace Settings</h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'devtrace_settings' ); ?>

                <table class="form-table">
                    <tr>
                        <th>Enable DevTrace</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    name="devtrace_active"
                                    value="1"
                                    <?php checked( 1, $isActive ); ?>
                                />
                                Enable error tracking and debugging
                            </label>
                        </td>
                    </tr>
                </table>

                <?php submit_button( 'Save Settings' ); ?>
            </form>
        </div>
        <?php
    }
}
