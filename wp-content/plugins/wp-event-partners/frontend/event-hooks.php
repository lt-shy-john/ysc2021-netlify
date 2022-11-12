<?php

	add_action( 'WEP_speaker_add_layouts', 'wep_speaker_layout' );

	function wep_speaker_layout(){

		$selectSpeakerLayout = get_theme_mod('one_page_conference_speaker_layouts','1');

		include dirname(__FILE__) . '/layout/speaker/speaker-layout-'.$selectSpeakerLayout.'.php';

	}

	add_action( 'WEP_sponsor_add_layouts', 'wep_sponsor_layout' );

	function wep_sponsor_layout(){

		$selectSponsorLayout = get_theme_mod('one_page_conference_sponsor_layouts','1');

		include dirname(__FILE__) . '/layout/sponsor/sponsor-layout-'.$selectSponsorLayout.'.php';
	}

	add_action( 'WEP_testimonial_add_layouts', 'wep_testimonial_layout' );

	function wep_testimonial_layout() {

		$testimonial_layout = get_theme_mod('testimonail_layouts','1');

		include dirname(__FILE__) . '/layout/testimonial/testimonial-layout-'. $testimonial_layout .'.php';
	}

	add_action( 'WEP_venue_add_layouts', 'wep_venue_layout' );

	function wep_venue_layout(){

		include dirname(__FILE__) . '/layout/venue/venue-layout-1.php';
	}

	add_action( 'WEP_schedule_add_layouts', 'wep_schedule_layout' );

	function wep_schedule_layout() {

	include dirname(__FILE__) . '/layout/schedule/schedule-layout-1.php';
	}

	add_action( 'WEP_organizers_add_layouts', 'WEP_organizers_layouts' );

	function WEP_organizers_layouts() {

		include dirname(__FILE__) . '/layout/organizers/organizers-layout-1.php';
	}
?>
