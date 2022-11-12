jQuery(function($){
	$('body').on('click', '.loadmore', function() {
 
		var button = $(this);
		var data = {
			'action': 'one_page_conference_loadmore',
			'page' : one_page_conference_loadmore_params.current_page,
		};
 
		$.ajax({
			url : one_page_conference_loadmore_params.ajaxurl,
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...');
			},
			success : function( data ) {
				if( data ) { 
					$( 'div.blog-list-block' ).append(data);
					button.text( 'More Posts' );
					one_page_conference_loadmore_params.current_page++;
 
					if ( one_page_conference_loadmore_params.current_page == one_page_conference_loadmore_params.max_page ) { 
						button.remove();
					}
				} else {
					button.remove();
				}
			}
		});
	});
});