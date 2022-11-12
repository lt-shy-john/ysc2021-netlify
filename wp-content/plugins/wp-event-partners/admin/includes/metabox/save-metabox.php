<?php
add_action('save_post', 'wep_save_social_metabox');
function wep_save_social_metabox() {
    global $post;

    if(isset($_POST["facebook_link"])) {
        update_post_meta($post->ID, "facebook_link", esc_url_raw($_POST["facebook_link"]) );
    }
    if(isset($_POST["instagram_link"])) {
        update_post_meta($post->ID, "instagram_link", esc_url_raw($_POST["instagram_link"]) );
    }

    if(isset($_POST["twitter_link"])) {
        update_post_meta($post->ID, "twitter_link", esc_url_raw($_POST["twitter_link"]) );
    }

    if(isset($_POST["linkedIn_link"])) {
        update_post_meta($post->ID, "linkedIn_link", esc_url_raw($_POST["linkedIn_link"]) );
    }
}

add_action('save_post', 'wep_save_schedule');

function wep_save_schedule($post_ID = 0)
{

    $post_ID = (int) $post_ID;
    if (isset($_POST["speakers"])) {
        update_post_meta($post_ID, "speakers", $_POST["speakers"] );
    }else {
        update_post_meta($post_ID, "speakers", '');
    }
    return $post_ID;


}
add_action('save_post', 'wep_save_image_custom_fields');
function wep_save_image_custom_fields() {
    global $post;
    if(isset($_POST["image_name"])) {
        update_post_meta($post->ID, "image_name", ($_POST["image_name"]) );
    }
    if(isset($_POST["photo_desc"])) {
        update_post_meta($post->ID, "photo_desc", ($_POST["photo_desc"]) );
    }
    if(isset($_POST["txt_image_url"])) {
        update_post_meta($post->ID, "txt_image_url", ($_POST["txt_image_url"]) );
    }

    if(isset($_POST["image_link"])) {
        update_post_meta($post->ID, "image_link", ($_POST["image_link"]) );
    }
    if(isset($_POST["position"])) {
        update_post_meta($post->ID, "position", sanitize_text_field($_POST["position"]) );
    }
    if(isset($_POST["company"])) {
        update_post_meta($post->ID, "company", sanitize_text_field($_POST["company"]) );
    }
    if(isset($_POST["website_link"])) {
        update_post_meta($post->ID, "website_link", esc_url_raw($_POST["website_link"]) );
    }
    if(isset($_POST["sub_title"])) {
        update_post_meta($post->ID, "sub_title", sanitize_text_field($_POST["sub_title"]) );
    }

}
