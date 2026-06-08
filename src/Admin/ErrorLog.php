<?php

namespace DevTrace\Admin;

class ErrorLog {

    /**
     * Render error logs page.
     *
     * @return void
     */
    public function render(): void {
        global $wpdb;

        $errors = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}devtrace_errors ORDER BY created_at DESC LIMIT 50"
        );
        ?>
        <div class="devtrace-wrap">
            <h1 class="devtrace-title">
                <?php echo esc_html__( 'Error Logs', 'wp-devtrace' ); ?>
            </h1>
            <div class="devtrace-table-wrap">
            <table class="devtrace-table">
                <thead>
                    <tr>
                        <th><?php echo esc_html__( 'Type', 'wp-devtrace' ); ?></th>
                        <th><?php echo esc_html__( 'Message', 'wp-devtrace' ); ?></th>
                        <th><?php echo esc_html__( 'File', 'wp-devtrace' ); ?></th>
                        <th><?php echo esc_html__( 'Line', 'wp-devtrace' ); ?></th>
                        <th><?php echo esc_html__( 'URL', 'wp-devtrace' ); ?></th>
                        <th><?php echo esc_html__( 'Date', 'wp-devtrace' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $errors ) ) : ?>
                        <tr>
                            <td colspan="6" class="devtrace-empty">
                                <?php echo esc_html__( 'No errors found.', 'wp-devtrace' ); ?>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ( $errors as $error ) : ?>
                            <tr>
                                <td>
                                    <span class="devtrace-badge devtrace-badge--<?php echo esc_attr( $error->type ); ?>">
                                        <?php echo esc_html( $error->type ); ?>
                                    </span>
                                </td>
                                <td class="devtrace-message">
                                    <span class="devtrace-message-short">
                                        <?php echo esc_html( wp_trim_words( $error->message, 10, '...' ) ); ?>
                                    </span>
                                    <span class="devtrace-message-full">
                                        <?php echo esc_html( $error->message ); ?>
                                    </span>
                                    <span class="devtrace-toggle">+ Show more</span>
                                </td>
                                <td class="devtrace-file"><?php echo esc_html( basename( $error->file ) ); ?></td>
                                <td><?php echo esc_html( $error->line ); ?></td>
                                <td><?php echo esc_html( $error->url ); ?></td>
                                <td><?php echo esc_html( $error->created_at ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
         </div>
        <?php
    }
}

