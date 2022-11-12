/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// General Options: Colors and Fonts

	wp.customize( 'header_bg_color', function( value ) {
		value.bind( function( to ) {
			$( '#masthead' ).css( 'background-color', to );
		} );
	} );

	wp.customize('primary_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--primary-color', to);
			}
		);
	}
	);

	wp.customize('secondary_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--secondary-color', to);
			}
		);
	}
	);

	wp.customize('text_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--text-color', to);
			}
		);
	}
	);

	wp.customize('accent_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--accent-color', to);
			}
		);
	}
	);

	wp.customize('light_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--light-color', to);
			}
		);
	}
	);

	wp.customize('dark_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--dark-color', to);
			}
		);
	}
	);

	wp.customize('grey_color',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--grey-color', to);
			}
		);
	}
	);

	wp.customize( 'site_color', function( value ) {
		value.bind( function( to ) {
			$( 'header .site-title a' ).css( 'color', to );
		} );
	} );

	// wp.customize( 'font_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( 'body' ).css( 'color', to );
	// 	} );
	// } );


	// wp.customize( 'menu_font_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( '.main-navigation ul li a, .footer .widget-title, .footer ul li, .footer ul li a, .copyright, .copyright p, .copyright a' ).css( 'color', to );
	// 	} );
	// } );

	// wp.customize( 'menu_background_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( '#masthead' ).css( 'background-color', to );
	// 		$( '.main-navigation .nav-menu' ).css( 'background-color', to );
	// 		$( '.main-navigation ul ul' ).css( 'background', to );
	// 	} );
	// } );


	// wp.customize( 'heading_title_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( 'h2.title-1,h3,h4.title-1,h5,h6' ).css( 'color', to );
	// 	} );
	// } );

	// wp.customize( 'heading_link_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( 'h2 a, h3 a, h4 a,h2 a:visited, h3 a:visited, h4 a:visited' ).css( 'color', to );
	// 	} );
	// } );


	// wp.customize( 'button_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( '.btn-primary' ).css( 'background-color', to );
	// 		$( '.btn-primary, .btn-outline-primary, .btn-outline-white ' ).css( 'border-color', to );
	// 		$( '.btn-outline-primary, .btn-outline-white ' ).css( 'color', to );
	// 	} );
	// } );

	// wp.customize( 'footer_background_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( 'footer, .copyright' ).css( 'background', to );
	// 		$( 'footer.main' ).css( 'background-color', to );
	// 	} );
	// } );

	// wp.customize( 'site_color', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( 'header .site-title a' ).css( 'color', to );
	// 		$( 'header .site-description' ).css( 'color', to );
	// 	} );
	// } );

	wp.customize( 'font_family', function( value ) {
		value.bind( function( to ) {
			$("head").append("<link href='https://fonts.googleapis.com/css?family=" + to + ":200,300,400,500,600,700,800,900|' rel='stylesheet' type='text/css'>");
			$( 'body,p' ).css( 'font-family', to );
		} );
	} );


	wp.customize( 'font_size', function( value ) {
		value.bind( function( to ) {
			$( 'body,p' ).css( 'font-size', to );
		} );
	} );


	wp.customize( 'site_identity_font_family', function( value ) {
		value.bind( function( to ) {
			$("head").append("<link href='https://fonts.googleapis.com/css?family=" + to + ":200,300,400,500,600,700,800,900|' rel='stylesheet' type='text/css'>");
			$( 'header .site-title a' ).css( 'font-family', to );
		} );
	} );

	// General Options: Heading Options


	wp.customize('heading_font_family',function ( value ) {
		value.bind(function ( to ) {
				document.body.style.setProperty('--font-family', to);
			}
		);
	} );


	wp.customize( 'blog_post_view', function( value ) {
		value.bind( function( to ) {
			$( '.blog-list-block' ).attr( 'class', 'blog-list-block' );
			$( '.blog-list-block' ).addClass( ' ' + to );
		} );
	} );

	wp.customize( 'event_title', function( value ) {
        value.bind( function( to ) {
            $( '.caption .title' ).text( to );
        } );
    } );

    wp.customize( 'event_title_font_size', function( value ) {
        value.bind( function( to ) {
            $( '#banner .caption .title' ).css( 'font-size', to + 'px' );
        } );
    } );

    wp.customize( 'event_description', function( value ) {
        value.bind( function( to ) {
            $( '.caption p' ).text( to );
        } );
    } );

    wp.customize( 'event_venue', function( value ) {
        value.bind( function( to ) {
            $( '.location' ).text( to );
        } );
    } );

	wp.customize( 'cta_1_button_label', function( value ) {
        value.bind( function( to ) {
            $( '.group-btn .cta-1' ).text( to );
        } );
    } );

    wp.customize( 'cta_2_button_label', function( value ) {
        value.bind( function( to ) {
            $( '.group-btn .cta-2' ).text( to );
        } );
    } );

} )( jQuery );
