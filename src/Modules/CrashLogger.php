<?php

namespace DevTrace\Modules;

class CrashLogger {

    /**
     * Register error handlers.
     *
     * @return void
     */
    public function register(): void {
        set_error_handler( [ $this, 'handleError' ] );
        register_shutdown_function( [ $this, 'handleShutdown' ] );
    }

    /**
     * Handle all errors.
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     * @return bool
     */
    public function handleError( int $errno, string $errstr, string $errfile, int $errline ): bool {
        $this->saveError( [
            'type'    => $this->getErrorType( $errno ),
            'message' => $errstr,
            'file'    => $errfile,
            'line'    => $errline,
        ] );

        return false;
    }

    /**
     * Handle PHP shutdown for fatals.
     *
     * @return void
     */
    public function handleShutdown(): void {
        $error = error_get_last();

        if ( ! $this->isFatal( $error ) ) {
            return;
        }

        $error['type'] = 'fatal';

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
     * Get error type string.
     *
     * @param int $errno
     * @return string
     */
    private function getErrorType( int $errno ): string {
        return match( $errno ) {
            E_WARNING, E_USER_WARNING        => 'warning',
            E_NOTICE, E_USER_NOTICE          => 'notice',
            E_DEPRECATED, E_USER_DEPRECATED  => 'deprecated',
            default                          => 'fatal',
        };
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

        if ( str_contains( $url, 'favicon.ico' ) ) {
            return;
        }

        $wpdb->insert(
            $wpdb->prefix . 'devtrace_errors',
            [
                'type'           => $error['type'] ?? 'fatal',
                'message'        => $error['message'],
                'file'           => $error['file'],
                'line'           => $error['line'],
                'url'            => $url,
                'active_plugins' => json_encode( get_option( 'active_plugins', [] ) ),
                'created_at'     => current_time( 'mysql' ),
            ]
        );
    }
}