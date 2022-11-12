jQuery(document).ready(function($) {
	jQuery("#speakers-1").owlCarousel({
		loop:false,
		nav:true,
		margin:30,
		dots: true,
		autoplay:true,
		autoplayHoverPause:true,	
		responsiveClass: true,
		responsive:{
			0:{
				items:2,
				margin:10
			},
			576:{
				items:2
			},
			768:{
				items:3
			},
			1025:{
				items:4
			}
		}
	})

	$('.testimonials-layout-1 .owl-carousel').owlCarousel({
		items:1,
		loop:false,
		nav:true,
		dots: true,
		autoplay:true,
		autoplayHoverPause:true
	})

	$('.testimonials-layout-2 .owl-carousel').owlCarousel({
		loop:false,
		nav:true,
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

