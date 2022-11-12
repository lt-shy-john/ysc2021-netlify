<?php
/**
 * Event Information Page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function event_information(){
    ?>
    <div class="top-info">
        <h1>WP Event Partners</h1>
        <h4>We help you build an Event and Conference website. Our plugin will cope with all your different needy elements required on your WordPress website. </h4>
        <p>The plugin is built such that it would help you manage sessions, speakers, schedules for your conference.</p>
        <a target="_blank" href="https://wpeventpartners.com/" class="button button-primary">Get more Information</a>
        <a target="_blank" href="https://wpeventpartners.com/docs" class="button button-secondary">View Documentation</a>
    </div>

    <div class="wep-themes">
    <div class="row">
        <div class="col-sm-5">
            <h3>Event Themes</h3>
            <p>If you need event website with all the robust features like Multi Days Schedule Management, Whitelable/Ability to Footer Copyright, Premium support, Event Tickets, Speakers & Session Details , Sponsors Information etc. Then you can checkout our Free & Premium Themes.</p>
            <div class="row">
                <div class="col-sm-6"><a target="_blank" href="https://wpeventpartners.com/layouts/corporate-events/"><img class="img-fluid" src="https://wpeventpartners.com/wp-content/uploads/2021/04/corporate-event.jpg"></a><h4>Corporate Event</h4><a target="_blank" href="https://wpeventpartners.com/" class="button button-primary">View Our Theme</a></div>
                <div class="col-sm-6"><a target="_blank" href="https://wpeventpartners.com/layouts/one-page-conference/"><img class="img-fluid" src="https://wpeventpartners.com/wp-content/uploads/2020/07/one-page-conference-design.jpg"></a><h4>One Page Conference</h4><a target="_blank" href="https://wpeventpartners.com/" class="button button-primary">View Our Theme</a></div>
            </div>
        </div>
    </div>
    </div>

    <div class="wrap" style="display: none;">
        <fieldset class="fieldset-event-info">

            <form method="post" action="options.php" class="field_form">
                <?php
              
                $event_title = get_option('event_title');
                $eventInfo = get_option('event_info') ;
                $event_venue = get_option('event_venue');
                $event_location = get_option('event_location');
                $event_venue_image = get_option('event_venue_image');
                $event_venue_description = get_option('event_venue_description') ;
                $event_google_map = get_option('event_google_map');
                $event_date_start = get_option('event_date_start');
                $event_date_end = get_option('event_date_end');
                $event_date_format = get_option('eventbanner_date_format');
                $event_date_format_custom = get_option('eventbanner_date_format_custom');
                $event_time_start = get_option('event_time_start');
                $event_time_end = get_option('event_time_end');
                $event_time_format = get_option('eventbanner_time_format');
                $event_time_format_custom = get_option('eventbanner_time_format_custom');
                $event_call_btn_name = get_option('event_call_btn_name');
                $event_call_for_paper = get_option('event_call_for_paper');
                $event_registration_btn_name = get_option('event_registration_btn_name');
                $event_registration = get_option('event_registration');
                $map_lng = get_option('map_lng');
                $map_lat = get_option('map_lat');
                ?>
                <div class="event-information">
                    <div class="row">
                        <div class="col-lg-8">
                            
                            <div class="event-info">

                            <legend style="display: none;">Event Information  <small>• For General Purpose</small></legend>

                            

                            <div class="form-group row" >
                                <label class="col-sm-3 col-form-label"><i class="fa fa-pencil-square-o"></i>
                                    Event Description:</label>
                                <div class="col-sm-9">
                                    <textarea rows="5" name="event_info" placeholder="Write info here.."><?php echo $eventInfo; ?></textarea>
                                </div>
                            </div>

                            <div class="date-block" >
                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-3 col-form-label"><i class="fa fa-calendar" aria-hidden="true"></i> Date:</label>
                                    <div class="col-sm-5 d-flex pl-0">
                                        <label class="col-sm-3 col-form-label">Start: </label>
                                        <div class="col-sm-9 p-0">
                                            <input type="date" name="event_date_start" value="<?php echo $event_date_start; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 row pr-0">
                                        <label class="col-sm-3 col-form-label">End: </label>
                                        <div class="col-sm-9 p-0">
                                            <input type="date" name="event_date_end" value="<?php echo $event_date_end; ?>">
                                        </div>
                                    </div>
                                </div>

                                
                            </div>

                            <div class="time-block">
                                <div class="form-group row" >
                                    <label class="col-sm-3 col-form-label"><i class="fa fa-clock-o" aria-hidden="true"></i> Time:</label>
                                    <div class="col-sm-5 d-flex pl-0">
                                        <label class="col-sm-3 col-form-label">Start: </label>
                                        <div class="col-sm-9 p-0">
                                            <input id="timepicker1" type="text" name="event_time_start" value="<?php echo $event_time_start; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 row pr-0">
                                        <label class="col-sm-3 col-form-label">End: </label>
                                        <div class="col-sm-9 p-0">
                                            <input id="timepicker2" type="text" name="event_time_end" value="<?php echo $event_time_end; ?>"/>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                                <div >
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><i class="fa fa-external-link" aria-hidden="true"></i> CTA 1:<br><small>CTA button for the main banner</small></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="event_call_btn_name" value="<?php echo $event_call_btn_name; ?>" placeholder="Button Text">
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="url" name="event_call_for_paper" value="<?php echo $event_call_for_paper; ?>" placeholder="Button Link">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><i class="fa fa-external-link" aria-hidden="true"></i> CTA 2:<br><small>CTA button for the main banner</small></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="event_registration_btn_name" value="<?php echo $event_registration_btn_name; ?>" placeholder="Button Text">
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="url" name="event_registration" value="<?php echo $event_registration; ?>" placeholder="Button Link">
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div style="display: none;">
                            <legend>Venue Information <small>• For Homepage Display</small></legend>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Event Venue:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="event_venue" value="<?php echo $event_venue; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><i class="fa fa-map-marker" aria-hidden="true"></i> Event Location:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="event_location" value="<?php echo $event_location; ?>">
                                </div>
                            </div>

                            <div class="form-group row" style="display: none;">
                                <label class="col-sm-3 col-form-label"><i class="fa fa-picture-o" aria-hidden="true"></i> Venue Image:</label>
                                <div class="col-sm-7">
                                    <input id="your_image_url" class="upload_image" type="text" size="36" name="event_venue_image" value="<?php echo $event_venue_image; ?>" />
                                    <div class="image-block image-holder"> <img src="<?php echo $event_venue_image; ?>" width="100" /> </div>
                                </div>
                                <div class="col-sm-2 col-sm-offset-3">
                                    <input class="your_image_url_button btn" type="button" value="Upload Image" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    Venue Description:</label>
                                <div class="col-sm-9">
                                    <textarea rows="5" name="event_venue_description" placeholder="Write info here.."><?php echo $event_venue_description; ?></textarea>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i> Location Map
                                </label>
                                <div class="col-sm-9">
                                    <div id="pac-container" class="row">
                                        <div class="col-sm-12">
                                            <div id="type-selector" class="pac-controls">
                                                <input type="radio" name="type" id="changetype-all" checked="checked">
                                                <label for="changetype-all">Search Loacation</label>
                                            </div>
                                            <input id="pac-input" type="text" placeholder="Search a location">
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" id="lat" name="map_lat" value="<?php echo $map_lat; ?>" placeholder="Enter Latitude"/>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" id="lng" name="map_lng" value="<?php echo $map_lng; ?>" placeholder="Enter Longitude"/>
                                        </div>
                                        <div class="col-sm-2">
                                            <input id="pac_btn" type="button" class="btn" value="Go to map">
                                        </div>
                                    </div>
                                    <div id="map"></div>
                                    
                                </div>
                            </div>
                            </div>
                        </div>
                       

                    </div>
                </div>

            </form>

        </fieldset>
    </div>
    <?php
}

function load_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_media_files' );

