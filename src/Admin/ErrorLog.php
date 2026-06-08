<?php

namespace DevTrace\Admin;

use DevTrace\Database;

class ErrorLog {

    /**
     * Render error logs page.
     *
     * @return void
     */
    public function render(): void {
        global $wpdb;

        $search = sanitize_text_field( $_GET['s'] ?? '' );
        $type   = sanitize_text_field( $_GET['type'] ?? '' );
        $date   = sanitize_text_field( $_GET['date'] ?? '' );

        $query  = "SELECT * FROM {$wpdb->prefix}devtrace_errors";
        
        $where = [];

        if ( $search ) {
            $where[] = $wpdb->prepare(
                'message LIKE %s',
                '%' . $wpdb->esc_like( $search ) . '%'
            );
        }

        if ( $type ) {
            $where[] = $wpdb->prepare( 'type = %s', $type );
        }

        if ( $date ) {
            $where[] = $wpdb->prepare( 'DATE(created_at) = %s', $date );
        }

        $query = "SELECT * FROM {$wpdb->prefix}devtrace_errors";

        if ( ! empty( $where ) ) {
            $query .= ' WHERE ' . implode( ' AND ', $where );
        }

        $query .= ' ORDER BY created_at DESC';
        $errors  = $wpdb->get_results( $query );
        ?>

        <div class="devtrace-wrap">

            <h1 class="devtrace-title">
                <?php echo esc_html__( 'Error Logs', 'wp-devtrace' ); ?>
            </h1>

            <form method="get">
                <input type="hidden" name="page" value="wp-devtrace-errors" />

                <input
                    type="text"
                    name="s"
                    value="<?php echo esc_attr( $search ); ?>"
                    placeholder="<?php echo esc_attr__( 'Search errors...', 'wp-devtrace' ); ?>"
                />

                <select name="type">
                    <option value=""><?php echo esc_html__( 'All Types', 'wp-devtrace' ); ?></option>
                    <?php foreach ( Database::getDistinctValues( 'type' ) as $errorType ) : ?>
                        <option value="<?php echo esc_attr( $errorType ); ?>">
                            <?php echo esc_attr( $errorType ); ?>
                        </option>
                    <?php endforeach; ?>
              </select>
              
                <input type="date" name="date" value="<?php echo esc_attr( $date ); ?>" />
                <?php submit_button( __( 'Filter', 'wp-devtrace' ), 'secondary', '', false ); ?>
            </form>

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
                                        <?php if ( str_word_count( $error->message ) > 10 ) : ?>
                                            <span class="devtrace-message-short">
                                                <?php echo esc_html( wp_trim_words( $error->message, 10, '...' ) ); ?>
                                            </span>
                                            <span class="devtrace-message-full">
                                                <?php echo esc_html( $error->message ); ?>
                                            </span>
                                            <span class="devtrace-toggle">+ Show more</span>
                                        <?php else : ?>
                                            <?php echo esc_html( $error->message ); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="devtrace-file">
                                        <?php echo esc_html( basename( $error->file ) ); ?>
                                    </td>
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