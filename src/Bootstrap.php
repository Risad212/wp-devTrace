<?php
/**
 * Bootstrap Whoops error handler.
 * Registers before everything else loads.
 * Check Activate or not
 */

if( get_option('devtrace_active') ) {
   $whoops  = new \Whoops\Run();
   $handler = new \Whoops\Handler\PrettyPageHandler();

   $handler->addDataTable( 'WordPress Context', [
      'URL'          => $_SERVER['REQUEST_URI'] ?? '',
      'WP Version'   => get_bloginfo( 'version' ),
      'Active Theme' => wp_get_theme()->get( 'Name' ),
      'Plugin Count' => count( get_option( 'active_plugins', [] ) ),
   ] );

   $whoops->pushHandler( $handler );
   $whoops->register();
}

