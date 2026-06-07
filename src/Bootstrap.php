<?php
/**
 * Bootstrap Whoops error handler.
 * Registers before everything else loads.
 * Catches all PHP errors and exceptions.
 */

if( get_option('devtrace_active') ){
   $whoops = new \Whoops\Run();
   $whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
   $whoops->register();
}


