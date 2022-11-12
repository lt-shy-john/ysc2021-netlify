<?php if( get_theme_mod( 'schedule_display_option', true ) && class_exists('WepPlugin') ) { ?>
    <section class="home-section schedule-block" id="schedule">
        <div class="container">
            <?php

                $scheduleHeading = get_theme_mod('heading_for_schedule');

                $scheduleContent = get_theme_mod('content_for_schedule');
            ?>

                 <div class="main-title sub-title">
                    <?php if (!empty($scheduleHeading)) { ?>
                    <h2 class="title title-1"><?php echo esc_html($scheduleHeading); ?></h2>
                    <?php } ?>
                    <?php if (!empty($scheduleContent)) { ?>
                    <span class="sub-title"><?php echo esc_html($scheduleContent);?></span>
                    <?php } ?>
                </div>

            <?php
                $selectScheduleLayout = get_theme_mod('one_page_conference_schedule_layout','1');

                if (class_exists('WepScheduleAddonPlugin')) {
                        do_action('WEP_schedule_addon_customize_layouts');
                } else {
                    do_action('WEP_schedule_add_layouts');
                }
            ?>
        </div>
    </section>
        
<?php
}