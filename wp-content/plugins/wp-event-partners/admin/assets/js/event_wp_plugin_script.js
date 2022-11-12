jQuery(document).ready(function($){
    jQuery('#timepicker1').timepicki();

    jQuery('#timepicker2').timepicki();
    //image upload for sponsor
    var mediaUploader;

    $('.upload_image_button').click(function(e) {
        // alert(1);
        e.preventDefault();
        var mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        mediaUploader.on('select', function () {
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = mediaUploader.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image

            var image_url = uploaded_image.toJSON().url;

            if (imageholder) {
                imageholder.attr('src', image_url);
            }
            formvar.val(image_url);
            tb_remove();

        });
        formvar = jQuery(this).prev('.upload_image');

        imageholder = jQuery(this).parent().find('.image-holder img');
        mediaUploader.open();

        return false;

    });

    jQuery(".btn-add-photo").click(function () {
        jQuery(".fieldset-photo #clone-input-list > li:last-child").clone(true).insertAfter(".fieldset-photo #clone-input-list > li:last-child");
        jQuery(".fieldset-photo #clone-input-list > li:last-child .image_name").val("");
        jQuery(".fieldset-photo #clone-input-list > li:last-child .image_desc").val("");
        jQuery(".fieldset-photo #clone-input-list > li:last-child .upload_image").val("");
        jQuery(".fieldset-photo #clone-input-list > li:last-child .image-holder img").attr("src", "");
        jQuery(".fieldset-photo #clone-input-list > li:last-child .image_link").val("");
        jQuery(".fieldset-photo #clone-input-list > li:last-child .image_new_tab").attr("checked", false);
        return false;
    });

//to remove the section
    jQuery(".btn-remove-image").click(function () {
        jQuery(this).parent().remove();
    });

//sortable
    $(".left_container").sortable({

    });



    //image upload for event management
    var image_custom_uploader;
    jQuery('.your_image_url_button').click(function (e) {
        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (image_custom_uploader) {
            image_custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        image_custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        image_custom_uploader.on('select', function () {
            attachment = image_custom_uploader.state().get('selection').first().toJSON();
            var url = '';
            url = attachment['url'];
            jQuery('#your_image_url').val(url);
            if (imageholder) {
                imageholder.attr('src', url);
            }
            formvar.val(url);
            tb_remove();
        });
        //Open the uploader dialog
        formvar = document.getElementById('your_image_url');

        imageholder = jQuery(this).parent().parent().find('.image-holder img');
        image_custom_uploader.open();
    });

});

jQuery(document).ready(function($){
    jQuery('#timeStart').timepicki(); //Initializes the time picker
    jQuery('#timeEnd').timepicki()


    //define template
    var template = $('.fieldset-schedule #clone-input-list > div:last-child').clone();
    var sectionsCount = 1;

    jQuery(".btn-add-schedule ").on('click', function() {

        //increment
        sectionsCount++;

        //loop through each input
        var section = template.clone().find(':input').each(function(){

            //set id to store the updated section number
            var newId = this.id + sectionsCount;

            //update for label
            $(this).prev().attr('for', newId);
            //update id
            this.id = newId;


        }).end()
            //inject new section
            .appendTo('#clone-input-list');
        jQuery(".fieldset-schedule #clone-input-list > div.clone-section:last-child .scheduleStart").val('');
        jQuery(".fieldset-schedule #clone-input-list > div.clone-section:last-child .scheduleEnd").val("");
        jQuery(".fieldset-schedule #clone-input-list > div.clone-section:last-child .scheduleSession").val("");


        jQuery(".btn-remove").click(function () {
            jQuery(this).parent().parent().parent().remove();
        });

        $('#timeStart'+sectionsCount).timepicki();
        $('#timeEnd'+sectionsCount).timepicki();
        return false;
    });

});

jQuery(".btn-remove").click(function () {
    jQuery(this).parent().parent().parent().remove();
});
