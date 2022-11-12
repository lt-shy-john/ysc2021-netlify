<?php 
get_header();
global $post;
$roomSlug = $post->post_name;
?>

<div class="inside-page content-area">
    <div class="row">
        <div class="col-md-4">
            <div class="img-holder">
                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="feature-image">
                        <?php the_post_thumbnail( 'medium' ); ?>
                    </figure>      
                <?php endif; ?>    
            </div>
        </div>
        <div class="col-md-8">
            <h3 class="title"><?php the_title(); ?></h3>
            <p><?php the_content(); ?></p>
        </div>
    </div> 

    <div class="session-list">
        <h1 class="title">Session</h1>
        <?php
            $scheduleSession[] = '';
            $roomSession[] = '';
            $scheduleSession = get_option('selectSession');
            $roomSession = get_option('selectRoom');
            $results = array_map( null, $scheduleSession, $roomSession );
            if($results){
                echo '<ul>';
                foreach ($results as $result){  
                    if(in_array($roomSlug,$result)){
                        $sessionposts = get_page_by_path($result[0], '', 'session');
                        $sessionID = $sessionposts->ID;
                        $sessionTitle = $sessionposts->post_title;
                        $permalink = get_the_permalink($sessionID);
                        echo "<li><a href=$permalink>'$result[0]'</a></li>";
                    }
                }
                echo '<ul>';
            }
            
            
        ?>
        <h4></h4>
    </div>      
</div>
<?php get_footer(); ?>