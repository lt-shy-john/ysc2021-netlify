<?php
    $venueHeading = get_theme_mod('heading_for_venue');
    $locationMap = get_theme_mod('venue_location_map');
    $event_venue = get_theme_mod('venue_name');
    $event_location = get_theme_mod('venue_location');
    $event_venue_image = get_theme_mod('event_venue_image');
    $event_venue_description = get_theme_mod('venue_description');
    ?>
    <section class="venue-layout-main venue-layout-1" id="venue" style="background-image: url('<?php echo esc_url( $event_venue_image ); ?>')">
    <div class="container">
                <?php if ($venueHeading) { ?>
                    <h2 class="title title-2"><?php echo esc_html($venueHeading); ?></h2>
                <?php } ?>
                
            <div class="venue-info-wrapper">
                    <div class="img-holder">
                        <?php
                        echo $locationMap;
                        ?>   
                    </div>
                    <div class="venue-content">
                        <?php if ($event_venue) { ?>
                            <h4 class="venue-title"><?php echo esc_html($event_venue); ?></h4>
                        <?php } ?>
                        <?php if ($event_location) { ?>
                            <span class="location"><span class="fa fa-map-marker"></span> <?php echo esc_html($event_location); ?></span>
                        <?php } ?>

                        <?php if ($event_venue_description) { ?>
                            <p><?php echo esc_html($event_venue_description); ?></p>
                        <?php } ?>
                    </div>
            </div>
            </div>
    </section>
