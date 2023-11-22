jQuery(document).ready(function($) {
    
    'use strict';
    
    var footerActions = $( '#customize-footer-actions' );
    // panelVisible();
    deviceready();


    footerActions.find( '.devices button' ).on( 'click', function( event ) {

			var overlay = $( '#xiro-full-overlay' ),
				devices = '';
			var	newDevice = $( event.currentTarget ).data( 'device' );


			footerActions.find( '.devices button' )
				.removeClass( 'active' )
				.attr( 'aria-pressed', false );

			footerActions.find( '.devices .preview-' + newDevice )
				.addClass( 'active' )
				.attr( 'aria-pressed', true );

            $.each( footerActions.find( '.devices button' ), function( i, item ) {
                var datadevice = $(item).data( 'device' );
                devices += ' preview-' + datadevice;
            } );

			overlay
				.removeClass( devices )
				.addClass( 'preview-' + newDevice );

            setstate('devicestate', newDevice);

		});

        $( '.panelsize' ).on( 'click', function() {
                var panelw = $( '#style-form' );
                panelw.toggleClass( 'w-full' );
                panelw.toggleClass( 'w-normal' );

            });


        $( '.collapse-sidebar' ).on( 'click', function() {
            setstatebool('paneVisible', !getstatebool('paneVisible'));
            panelVisible();

		});


        function panelVisible(){
            var overlay = $( '#xiro-full-overlay' );

			var paneVisible = getstatebool('paneVisible');
            
			overlay.toggleClass( 'expanded', !paneVisible ); // show when value is false
            overlay.toggleClass( 'preview-only', paneVisible ); // true for hide
			overlay.toggleClass( 'collapsed', paneVisible );
        }

        function deviceready() {
            var devicestate = getstate('devicestate');
            var overlay = $( '#xiro-full-overlay' ),
				devices = '';
            if (devicestate) {
                $.each( footerActions.find( '.devices button' ), function( i, item ) {
                    var datadevice = $(item).data( 'device' );
                    devices += ' preview-' + datadevice;
                } );
    
                overlay
                    .removeClass( devices )
                    .addClass( 'preview-' + devicestate );
            }
        }

        function getstatebool(name) {

            var cond = localStorage.getItem(name);
            if (cond === null) {
                cond = false;
            } else {
                cond = JSON.parse(cond)
            }
            return cond;
        }
        function getstate(name) {

            var cond = localStorage.getItem(name);
            if (cond === null) {
                cond = false;
            } 
            return cond;
        }
        function setstatebool(name, value) {
            setstate(name, value);
        }
        function setstate(name, value) {
            localStorage.setItem(name, value);
        }
        
    });