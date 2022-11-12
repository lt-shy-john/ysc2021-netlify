jQuery(document).ready(function($) {

	if ( $('body').hasClass('home')) {

		$('.main-navigation ul li a').each( function() {
			var url = $(this).attr('href');
			var hash = url.split('#')[1];
			if( hash ) {
				$(this).attr( 'data-scroll', 'scroll' );
			}
		} );
		
		$('.one-page-menu a').each( function() {
			var url = $(this).attr('href');
			var hash = url.split('#')[1];
			if( hash ) {
				$(this).attr( 'data-scroll', 'scroll' );
			}
		} );

		function one_page_conference_scroll_to_section(event) {
			event.preventDefault();
			var $section = $( $(this).attr('href') ); 
			console.log(1);
			console.log($section);

			$('html, body').animate({
				scrollTop: $section.offset().top
			}, 500);
		}

		$('[data-scroll]').on('click', one_page_conference_scroll_to_section);
	}else {
		
		$('.main-navigation ul li a').each( function() {
			var url = $(this).attr('href');
			var hash = url.split('#')[1];
			console.log(hash);
			var homeUrl = home_url.homePage + '/#' + hash;
			if( hash ) {
				$(this).attr("href", homeUrl);
			}
		
		});
	}


	jQuery("#speakers-1").owlCarousel({
		loop:false,
		nav:true,
		margin:30,
		dots: false,
		autoplay:true,
		autoplayHoverPause:true,	
		responsiveClass: true,
		responsive:{
			0:{
				items:2,
				margin:10
			},
			576:{
				items:2,
				margin:30
			},
			768:{
				items:3
			},
			992:{
				items:4
			}
		}
	})

	$('.testimonials-layout-1 .owl-carousel').owlCarousel({
		items:1,
		loop:true,
		nav:false,
		dots: true,
		autoplay:true,
		autoplayHoverPause:true
	})

	$('.testimonials-layout-2 .owl-carousel').owlCarousel({
		loop:true,
		nav:false,
		dots: true,
		autoplay:true,
		autoplayHoverPause:true,
		responsive:{
			0:{
				items:1,
				margin:10
			},
			576:{
				items:2
			}
		}
	})

	$('.testimonials-layout-3 .owl-carousel').owlCarousel({
		loop:true,
		nav:false,
		dots: true,
		autoplay:true,
		autoplayHoverPause:true,
		responsive:{
			0:{
				items:1,
				margin:10
			},
			576:{
				items:2
			},
			768:{
				items:3
			},
		}
	})

	$('.testimonials-layout-4 .owl-carousel').owlCarousel({
		loop:true,
		nav:false,
		dots: true,
		autoplay:true,
		autoplayHoverPause:true,
		responsive:{
			0:{
				items:1,
				margin:10
			},
			576:{
				items:2
			}
		}
	})

} );

function one_page_conference_adjust_margin($) { 
		                
		var headerHeight = jQuery('header#masthead').height() + 'px';
		jQuery('body.sticky').css('margin-top', headerHeight);
		            
}

jQuery(window).resize(one_page_conference_adjust_margin); 
jQuery(window).ready(one_page_conference_adjust_margin); 
