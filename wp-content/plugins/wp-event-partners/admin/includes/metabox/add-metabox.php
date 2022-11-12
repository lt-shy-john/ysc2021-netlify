<?php
/*=======================================================================*/
// Custom fields
/*=======================================================================*/

add_action("admin_init", "wep_addMetabox_init");
function wep_addMetabox_init(){

    //metabox for social media
    add_meta_box("social_media", "Social Media link", "wep_build_social_media_link", "speaker", "advanced", "default");
    add_meta_box("social_media", "Social Media link", "wep_build_social_media_link", "testimonial", "advanced", "default");
    add_meta_box("social_media", "Social Media link", "wep_build_social_media_link", "wep_organizer", "advanced", "default");

    //metabox for position
    add_meta_box("Speaker Detail", "Position", "wep_build_person_detail", "speaker", "advanced", "default");
    add_meta_box("position", "Position", "wep_build_person_detail", "testimonial", "advanced", "default");

    //metabox for carousel
    add_meta_box("carousel", "Images", "wep_build_carousel_photos", "sponsor", "advanced", "default");

    //metabox for schedule
    add_meta_box("speaker", "Speakers", "wep_build_speaker", "session", "advanced", "default");

    //metabox for subtitle
    add_meta_box("sub_title", "Sub Title", "wep_build_sub_title", "wep_organizer", "advanced", "default");

   }

   function wep_build_sub_title(){
    global $post;

    $custom = get_post_custom(get_the_ID());

    if (!empty($custom)){
        if(isset($custom['sub_title'])){
            $sub_title = $custom['sub_title'][0];
        }
       
    }
    ?>

    <fieldset class="fieldset-1 fieldset-userDetail">
        <div class="testimonial-section">
            <div class="group">
                <label>Sub Title</label>
                <input type="text" name="sub_title" value="<?php if(isset($sub_title)) echo $sub_title;?>">

            </div>
        </div>
    </fieldset>

<?php }


//position metafield
function wep_build_person_detail(){
    global $post;

    $custom = get_post_custom(get_the_ID());

    if (!empty($custom)){
        if(isset($custom['position'])){
            $position = $custom['position'][0];
        }
        if(isset($custom['company'])){
            $company = $custom['company'][0];
        }
        if(isset($custom['website_link'])) {
            $websiteLink = $custom['website_link'][0];
        }
    }
    ?>

    <fieldset class="fieldset-1 fieldset-userDetail">
        <div class="testimonial-section">
            <div class="group">
                <label>Position</label>
                <input type="text" name="position" value="<?php if(isset($position)) echo $position;?>">
                <label>Company</label>
                <input type="text" name="company" value="<?php if(isset($company)) echo $company;?>">
                <label>Website Link</label>
                <input type="url" name="website_link" value="<?php if(isset($websiteLink)) echo $websiteLink;?>">

            </div>
        </div>
    </fieldset>

<?php }

//speaker metafield
function wep_build_speaker() {
    global $post;
    $custom = get_post_custom(get_the_ID());
    if(isset($custom['speakers'])){
        $speakers_serializeD = $custom['speakers'][0];
        if(is_serialized($speakers_serializeD)){
            $selectSpeakers = unserialize($custom["speakers"][0]);
        }else{
            $selectSpeakers = $custom["speakers"];
        }
    }
    ?>
    <fieldset class="fieldset-2 fieldset-itinerary related_pages test">
        <ul type="" id="clone-input-list" class="clonedInput left_container" >
            <?php
                $args = wep_pass_query_parameters(-1, 'speaker', 'title', 'ASC', false, '', '');
                $speakers = query_posts($args);
                if ($speakers) {
                    foreach ($speakers as $speaker) {
                        $speakerSlug = $speaker->post_name;
                        $speakerId = $speaker->ID;
                        $speakerTitle = $speaker->post_title;
            ?>
                        <li>
                            <label class="select_solution">
                                <input type="checkbox" name="speakers[]" value="<?php echo $speakerSlug ?>" <?php echo ( !empty( $selectSpeakers ) && in_array( $speakerSlug, $selectSpeakers ) ? ' checked' : '' ); ?>><?php echo $speakerTitle; ?>
                            </label>
                        </li>
            <?php } } ?>
        </ul>
    </fieldset>
    <?php
}

//social media metafield
function wep_build_social_media_link(){
    global $post;
    $custom = get_post_custom(get_the_ID());
    if (!empty($custom)){
        if(isset($custom['facebook_link'])){
            $facebookLink = $custom['facebook_link'][0];
        }
        if(isset($custom['instagram_link'])){
            $instagram_link = $custom['instagram_link'][0];
        }
        if(isset($custom['twitter_link'])){
            $twitter_link = $custom['twitter_link'][0];
        }
        if(isset($custom['linkedIn_link'])){
            $linkedIn_link = $custom['linkedIn_link'][0];
        }
    }

    ?>

    <fieldset class="fieldset-1 fieldset-userDetail">
        <div class="social-media-section">
            <div class="group">
                <label>Facebook Link</label>
                <input type="url" name="facebook_link" value="<?php if(isset($facebookLink)) echo $facebookLink;?>"><br>
                <label>Instagram Link</label>
                <input type="url" name="instagram_link" value="<?php if(isset($instagram_link)) echo $instagram_link;?>"><br>
                <label>Twitter Link</label>
                <input type="url" name="twitter_link" value="<?php if(isset($twitter_link)) echo $twitter_link;?>"><br>
                <label>LinkedIn Link</label>
                <input type="url" name="linkedIn_link" value="<?php if(isset($linkedIn_link)) echo $linkedIn_link;?>"><br>
            </div>
        </div>

    </fieldset>

<?php }

//caousel metafield
function wep_build_carousel_photos() {
    global $post;
    $custom = get_post_custom(get_the_ID());
    if (!empty($custom['txt_image_url'])) {
        $photo_url_serializeD = $custom['txt_image_url'][0];
        if(is_serialized($photo_url_serializeD)){
            $photo_url = unserialize($photo_url_serializeD);
            $photo_name = unserialize($custom["image_name"][0]);
            $photo_desc = unserialize($custom["photo_desc"][0]);
            $photo_link = unserialize($custom['image_link'][0]);
        }else{
            $photo_url =  $custom['txt_image_url'];
            $photo_name = $custom['image_name'];
            $photo_desc = $custom["photo_desc"];
            $photo_link = $custom['image_link'];
        }
    }

    ?>

    <fieldset class="fieldset-1 fieldset-photo">
        <ol type="1" id="clone-input-list" class="clonedInput left_container">
            <?php

            if(!empty($photo_url[0])) {
                for($i=0; $i<count($photo_url); $i++) {
                    if ($photo_name[$i] OR $photo_desc[$i] OR $photo_url[$i] OR $photo_link[$i]){
                        ?>
                        <li class="clone-section group page_item">
                            <div class="field-section">
                                <div class="group">
                                    <label>Company Name</label>
                                    <input class="image_name" type="text" value="<?php echo $photo_name[$i]; ?>" name="image_name[]">
                                    <label>Description</label>
                                    <textarea class="image_desc" name="photo_desc[]"><?php echo $photo_desc[$i]; ?></textarea><div class="silde_label">Upload or type the Image URL</div>
                                    <label>Logo</label>
                                    <input class="upload_image text_field" type="text" name="txt_image_url[]" value="<?php echo $photo_url[$i]; ?>" />
                                    <input class= "upload_image_button" type="button" value="Browse" />
                                    <div class="image-block image-holder"> <img src="<?php echo $photo_url[$i]; ?>" width="100" /> </div>
                                    <label>URL</label>
                                    <input class="image_link" type="url" value="<?php echo $photo_link[$i]; ?>" name="image_link[]">

                                </div>
                            </div>
                            <span class="text-btn btn-remove-image">Remove</span>
                        </li>
                    <?php }
                }
            } else { ?>
                <li class="clone-section group">
                    <div class="field-section">

                        <div class="group">
                            <label>Company Name</label>
                            <input class="image_name" type="text" name="image_name[]">
                            <label>Description</label>
                            <textarea class="image_desc" name="photo_desc[]"></textarea>
                            <div class="silde_label">Upload or type the Image URL </div>
                            <label>Logo</label>
                            <input class="upload_image text_field" type="text" name="txt_image_url[]" />
                            <input class= "upload_image_button" type="button" value="Browse" />
                            <div class="image-block image-holder"><img width="100" /> </div>
                            <label>URL</label>
                            <input class="image_link" type="url" name="image_link[]">
                        </div>
                    </div>
                    <span class="text-btn btn-remove-image">Remove</span>
                </li>
                <?php
            }
            ?>
        </ol>
        <div class="add-more-section"> <span class="btn-add-photo btn-add-video text-btn" style="cursor:pointer">+ Add New Photo</span> </div>
    </fieldset>
    <?php
}
