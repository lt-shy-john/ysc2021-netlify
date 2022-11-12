<?php
/**
 * Schedule Management Page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @since 1.0
 * @return void
 */
function schedule_management_options() {
?>

    <div class="wrap">
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Hey! Would you like to build a website for your Multi-Day and Multi-Track event?<br>
            Explore our free and premium theme designed specially for your kind of events.

            <a href="https://wpeventpartners.com/" class="btn btn-warning pull-right" target="_blank">Explore Themes</a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <fieldset class="fieldset-schedule">
            <form method="post" action="options.php" class="field_form">
                <h3>Schedule Management :</h3>
                <?php
                    settings_fields( 'event_information' );
                    do_settings_sections( 'event_information' );
                    $event_date_format = get_option('eventbanner_date_format');
                    $event_time_format = get_option('eventbanner_time_format');
                ?>
               
                
                    <div class="form-group row" style="display: none;">
                        <label class="col-sm-3 col-form-label">Date Format</label>
                        <div class="col-sm-9">
                            <select name="eventbanner_date_format">
                                <option value="">Date format</option>
                                <option value="F j, Y" <?php echo ($event_date_format == 'F j, Y')?"selected":"" ?>>February 13, 2020</option>
                                <option value="Y-m-d" <?php echo ($event_date_format == 'Y-m-d')?"selected":"" ?>>2020-02-13</option>
                                <option value="m/d/Y" <?php echo ($event_date_format == 'm/d/Y')?"selected":"" ?>>02/13/2020</option>
                                <option value="d/m/Y" <?php echo ($event_date_format == 'd/m/Y')?"selected":"" ?>>13/02/2020</option>
                                <option value="M j, Y" <?php echo ($event_date_format == 'M j, Y')?"selected":"" ?>>Feb 13, 2020</option>

                            </select>

                        </div>
                    </div>


                    <div class="form-group row" style="display: none;">
                        <label class="col-sm-3 col-form-label">Time Format</label>
                        <div class="col-sm-9">
                            <select name="eventbanner_time_format">
                                <option value="">Time format</option>
                                <option value="g:i a" <?php echo ($event_time_format == 'g:i a')?"selected":"" ?>>1:08 pm</option>
                                <option value="g:i a" <?php echo ($event_time_format == 'g:i a')?"selected":"" ?>>1:08 PM</option>
                                <option value="H:i" <?php echo ($event_time_format == 'H:i')?"selected":"" ?>>13:08</option>

                            </select>
                        </div>
                    </div>
                


                
                <label>Select Timezone</label>
                <?php
                settings_fields( 'event_timezone' );
                do_settings_sections( 'event_timezone' );
                $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                $timezoneSelect = get_option("timezone_setting")?get_option("timezone_setting"):"Asia/Kathmandu";
                ?>
                <select name='timezone_setting'>
                    <option>Select Timezone</option>
                <?php
                foreach($timezones as $timezone){ 
                    $selected_zone = " ";
                    if( $timezone == $timezoneSelect ){
                        $selected_zone = ' selected ';
                    }
                echo "<option". $selected_zone ."value='" . $timezone .  
                            "'>" . $timezone . "</option>";
                }
                echo "</select>";
                submit_button();
                ?>
            </form>
            
            <form method="post" action="options.php" class="field_form">
             <?php
                settings_fields( 'event_scheduleManagement' );
                do_settings_sections( 'event_scheduleManagement' );
                ?>
                <?php
                $scheduleSession[] = '';
                $scheduleDate = get_option('session_date') ;
                $scheduleStart = get_option('session_time_start') ;
                $scheduleEnds= get_option('session_time_end') ;
                $scheduleSession = get_option('selectSession') ;
                $roomSession = get_option('selectRoom');
                ?>
                <div class="schedule-heading">
                    <div class="row">
                        <div class="col-sm-2"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Select Date</div>
                        <div class="col-sm-2"><i class="fa fa-clock-o" aria-hidden="true"></i> Starts From</div>
                        <div class="col-sm-2"><i class="fa fa-clock-o" aria-hidden="true"></i> Ends at</div>
                        <div class="col-sm-3"><i class="fa fa-check-square-o" aria-hidden="true"></i> Select Session</div>
                        <div class="col-sm-2"><i class="fa fa-university" aria-hidden="true"></i> Select Room</div>
                        <div class="col-sm-1">Action</div>
                    </div>
                </div>

                <div type="1" id="clone-input-list" class="clonedInput left_container">
                    <?php
                   
                    if(!empty($scheduleSession)) {
                        for($i=0; $i<count($scheduleSession); $i++) {
                            ?>
                            <div class="clone-section group page_item">

                                <div class="row">
                                    <div class="col-sm-2"><input type="date" class="scheduleDate" name="session_date[]" value="<?php echo $scheduleDate[$i]; ?>"/></div>
                                    <div class="col-sm-2"><input type="text" id="timeStart"  name="session_time_start[]" class="scheduleStart" value="<?php echo $scheduleStart[$i]; ?>"/></div>
                                    <div class="col-sm-2"><input type="text" id="timeEnd" name="session_time_end[]" class="scheduleEnd" value="<?php echo $scheduleEnds[$i]; ?>"/></div>
                                    <div class="col-sm-3">
                                        <select name="selectSession[]" class="scheduleSession" required>
                                            <option value="">Choose session</option>
                                            <?php
                                            $args = wep_pass_query_parameters(-1, 'session', 'title', 'ASC', false);
                                            $sessions = query_posts($args);
                                            if ($sessions) {
                                                foreach ($sessions as $session) {
                                                    $sessionId = $session->ID;
                                                    $sessionSlug = $session->post_name;
                                                    $title = $session->post_title;
                                                    if ($sessionSlug == $scheduleSession[$i]){
                                                        $selected = 'selected';
                                                    }else{
                                                        $selected = '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $sessionSlug?>" <?php echo $selected; ?>><?php echo $title; ?></option>

                                                <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="selectRoom[]" class="scheduleSession">
                                            <option value="">Choose Room</option>
                                            <?php
                                            $args = wep_pass_query_parameters(-1, 'room', 'title', 'ASC', false);
                                            $rooms = query_posts($args);
                                            if ($rooms) {
                                                foreach ($rooms as $room) {
                                                    $roomId = $room->ID;
                                                    $roomSlug = $room->post_name;
                                                    $title = $room->post_title;
                                                    if ($roomSlug == $roomSession[$i]){
                                                        $selected = 'selected';
                                                    }else{
                                                        $selected = '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $roomSlug?>" <?php echo $selected; ?>><?php echo $title; ?></option>

                                                <?php }
                                            }
                                            ?>
                                        </select></div>
                                    <div class="col-sm-1 text-center">
                                        <span class="btn-remove"  style="cursor:pointer"><i class="fa fa-times" aria-hidden="true"></i></span>
                                    </div>
                                </div>

                            </div>

                            <?php
                        } }
                    else{
                        ?>
                            <div class="clone-section group page_item">
                                <div class="row">
                                    <div class="col-sm-2"><input type="date" class="scheduleDate" name="session_date[]" value=""/></div>
                                    <div class="col-sm-2"><input type="text" id="timeStart"  name="session_time_start[]" class="scheduleStart" value=""/></div>
                                    <div class="col-sm-2"><input type="text" id="timeEnd" name="session_time_end[]" class="scheduleEnd" value=""/></div>
                                    <div class="col-sm-3">
                                        <select name="selectSession[]" class="scheduleSession" required>
                                            <option value="">Choose session</option>
                                            <?php
                                            $args = wep_pass_query_parameters(-1, 'session', 'title', 'ASC', false);
                                            $sessions = query_posts($args);
                                            if ($sessions) {
                                                foreach ($sessions as $session) {
                                                    $sessionId = $session->ID;
                                                    $sessionSlug = $session->post_name;
                                                    $title = $session->post_title;
                                                    ?>
                                                    <option value="<?php echo $sessionSlug?>"><?php echo $title; ?></option>

                                                <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="selectRoom[]" class="scheduleSession">
                                            <option value="">Choose Room</option>
                                            <?php
                                            $args = wep_pass_query_parameters(-1, 'room', 'title', 'ASC', false);
                                            $rooms = query_posts($args);
                                            if ($rooms) {
                                                foreach ($rooms as $room) {
                                                    $roomId = $room->ID;
                                                    $roomSlug = $room->post_name;
                                                    $title = $room->post_title;
                                                    ?>
                                                    <option value="<?php echo $roomSlug?>" ><?php echo $title; ?></option>

                                                <?php }
                                            }
                                            ?>
                                        </select></div>

                                </div>
                            </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="-more-section"> <span class="btn-add-schedule text-btn" style="cursor:pointer">+  Add New Schedule</span> </div>
                <?php 
                if(!empty($scheduleDate[0])) {?>
                    <script type='text/javascript'>
                        jQuery(document).ready(function($) {
                            jQuery('.scheduleStart').timepicki();
                            jQuery('.scheduleEnd').timepicki();
                        });
                    </script>

                <?php } ?>
                <?php submit_button(); ?>
            </form>

        </fieldset>
    </div>

    <?php
    echo ob_get_clean();
}
