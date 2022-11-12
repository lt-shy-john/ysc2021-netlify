<header id="masthead"  class="site-header  header-layout-<?php echo esc_attr( $class ); ?>">
    <div class="header">
        <div class="container">
            <div class="header-wrapper">
                <div class="site-branding-social">
                    <div class="site-branding">
                        <?php
                        the_custom_logo(); ?>
                        <?php $siteTitleShow = get_theme_mod('one_page_conference_site_title');
                        
                        if($siteTitleShow){ ?>
                        <div class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                        </div>
                        <?php } ?>
                        <?php
                        $siteDescShow = get_theme_mod('one_page_conference_tagline');
                        if($siteDescShow){
                        $bootstrap_photography_description = get_bloginfo( 'description', 'display' );
                        if ( $bootstrap_photography_description || is_customize_preview() ) :
                            ?>
                            <p class="site-description"><?php echo $bootstrap_photography_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php  endif; } ?>
                    </div><!-- .site-branding -->


                    <div class="social-navigation">
                    <?php $social_media_array = get_theme_mod( 'one_page_conference_social_media', 'facebook' ); ?>

                    <?php if ( ! empty( $social_media_array ) && is_array( $social_media_array ) ) : ?>

                    <div class="social-icons">
                        <ul class="list-inline">
                            <?php
                            foreach ( $social_media_array as $value ) {
                                $key_classes = $value['social_media_repeater_class'];
                                $class = str_replace( " ", "-", strtolower( $key_classes ) );
                                $i_tag_class = 'icon-' . $class;
                                ?>
                                <li class="<?php echo esc_attr( strtolower( $class ) ); ?>">
                                    <a href="<?php echo esc_url( $value['social_media_link'] ); ?>" target="_blank">
                                        <i class="<?php echo esc_attr( $i_tag_class ); ?>"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                <?php endif; ?>
                <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><!-- <?php esc_html_e( 'Primary Menu', 'one-page-conference' ); ?> -->
                    <div id="nav-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'main-menu',
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
            </div>
            </div>


            
        </div>
    </div>
</div>
</header><!-- #masthead -->



