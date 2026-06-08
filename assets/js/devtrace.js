
// long error message toggle
jQuery( document ).ready( function( $ ) {

    $( document ).on( 'click', '.devtrace-toggle', function() {
        const td     = $( this ).closest( 'td' );
        const short  = td.find( '.devtrace-message-short' );
        const full   = td.find( '.devtrace-message-full' );
        const toggle = $( this );

        if ( full.is( ':visible' ) ) {
            full.hide();
            short.show();
            toggle.text( '+ Show more' );
        } else {
            full.show();
            short.hide();
            toggle.text( '- Show less' );
        }
    } );

} );