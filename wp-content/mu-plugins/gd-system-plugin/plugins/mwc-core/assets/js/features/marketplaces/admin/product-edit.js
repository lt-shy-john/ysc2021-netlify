if (typeof jQuery !== 'undefined') {
	jQuery(function($) {
		let currentProductStatus = $('#post_status').val();

		$('#delete-action, #publish').on('click', function(event) {
			// confirmation should be done when clicking on the delete link or when the post status was changed
			let firedEventRequiresConfirmation = 'delete-action' === $(this).attr("id") || $('#post_status').val() !== currentProductStatus;
			let productHasListings = gdMarketplacesProductEdit && '1' === gdMarketplacesProductEdit.productHasListings;

			if (
				firedEventRequiresConfirmation &&
				productHasListings &&
				! window.confirm(gdMarketplacesProductEdit.i18n.unpublishConfirmationMessage)
			) {
				event.preventDefault();
			}
		});
	});

	jQuery(function($) {

		$('button.mwc-marketplaces-create-draft-listing').on('click', (event) => {

			event.preventDefault();

			const channelType = event.target.dataset.channelType;
			const channelUuid = event.target.dataset.channelUuid;
			const thisChannelPanel = $(`#gd-marketplaces-${channelType}`);
			const thisChannelErrorWrapper = thisChannelPanel.find('.gd-marketplaces-create-draft-error');

			thisChannelErrorWrapper.html('').hide();

			thisChannelPanel.block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			$.post({
				url: gdMarketplacesProductEdit.ajaxUrl,
				data: {
					channelUuid: channelUuid,
					action: gdMarketplacesProductEdit.createDraftAction,
					nonce: gdMarketplacesProductEdit.createDraftNonce,
					productId: gdMarketplacesProductEdit.productId
				}
			}).done((response) => {

				if (! response.success) {
					thisChannelErrorWrapper.html(`<p>${response.data}</p>`).show();
					return;
				}

				thisChannelPanel.html(response.data);

			}).fail(() => {

				thisChannelErrorWrapper.html(`<p>${gdMarketplacesProductEdit.i18n.createDraftGenericError}</p>`).show();

			}).always(() => {

				thisChannelPanel.unblock();
			});
		});
	});
}
