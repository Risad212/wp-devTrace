<?php
/**
 * Bootstrap Whoops error handler.
 * Registers before everything else loads.
 * Check Activate or not
 */

if ( ! get_option( 'devtrace_active' ) ) {
    return;
}

$whoops  = new \Whoops\Run();
$handler = new \Whoops\Handler\PrettyPageHandler();

$handler->addDataTable( 'WordPress Context', [
    'URL'          => $_SERVER['REQUEST_URI'] ?? '',
    'WP Version'   => get_bloginfo( 'version' ),
    'Active Theme' => wp_get_theme()->get( 'Name' ),
    'Plugin Count' => count( get_option( 'active_plugins', [] ) ),
] );

// check user admin
$whoops->pushHandler( function( $exception, $inspector, $run ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return \Whoops\Handler\Handler::DONE;
    }
} );

$whoops->pushHandler( $handler );
$whoops->register();

