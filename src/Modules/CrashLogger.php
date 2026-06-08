<?php

namespace DevTrace\Modules;

class CrashLogger {

    /**
     * Register shutdown function.
     *
     * @return void
     */
    public function register(): void {
        register_shutdown_function( [ $this, 'handleShutdown' ] );
    }

    /**
     * Handle PHP shutdown.
     *
     * @return void
     */
    public function handleShutdown(): void {
        $error = error_get_last();

        if ( ! $this->isFatal( $error ) ) {
            return;
        }

        $this->saveError( $error );
    }

    /**
     * Check if error is fatal.
     *
     * @param array|null $error
     * @return bool
     */
    private function isFatal( ?array $error ): bool {
        if ( empty( $error ) ) {
            return false;
        }

        $fatalTypes = [ E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR ];

        return in_array( $error['type'], $fatalTypes, true );
    }

    /**
     * Save error to database.
     *
     * @param array $error
     * @return void
     */
    private function saveError( array $error ): void {
        global $wpdb;

       $url = $_SERVER['REQUEST_URI'] ?? '';

       // skip faveicon
        if ( str_contains( $url, 'favicon.ico' ) ) {
            return;
        }

        $wpdb->insert(
            $wpdb->prefix . 'devtrace_errors',
            [
                'type'           => 'fatal',
                'message'        => $error['message'],
                'file'           => $error['file'],
                'line'           => $error['line'],
                'stack_trace'    => json_encode( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) ),
                'url'            => $_SERVER['REQUEST_URI'] ?? '',
                'active_plugins' => json_encode( get_option( 'active_plugins', [] ) ),
                'created_at'     => current_time( 'mysql' ),
            ]
        );
    }
}