jQuery(($) => {

	'use strict';

	/**
	 * Wallets handler.
	 *
	 * Interacts with the Poynt Collect API to process payments using Apple and Google Pay.
	 */
	window.MWCPaymentsWalletsHandler = class MWCPaymentsWalletsHandler {

		/**
		 * Instantiates the Wallet on the current page.
		 *
		 * @param {Object} args form handler arguments
		 */
		constructor( args ) {

			this.appId            = args.appId;
			this.businessId       = args.businessId;
			this.isLoggingEnabled = args.isLoggingEnabled;
			this.apiUrl           = args.apiUrl;
			this.apiNonce         = args.apiNonce;
			this.initialized      = false;
			this.enabledButtons   = args.enabledButtons;

			// bail if no Wallet wrappers or enabled buttons exist on the page
			if (! $('#mwc-payments-wallet-buttons').length || ! Object.keys(this.enabledButtons).length) {
				return;
			}

			if ($('form.cart').length) {
				this.uiElement = $('form.cart');
				this.handleProductPage();
			} else if ($('form.woocommerce-cart-form').length) {
				this.uiElement = $('form.woocommerce-cart-form').parents('div.woocommerce');
				this.handleCartPage();
			} else if ($('form.woocommerce-checkout').length) {
				this.uiElement = $('form.woocommerce-checkout');
				this.handleCheckoutPage();
			} else {
				this.debugLog('No payment form available');
			}
		}

		/**
		 * Handles setting up Wallet on the single product pages.
		 */
		handleProductPage() {
			this.debugLog('Initializing the product page');

			if (this.uiElement.hasClass('variations_form')) {
				this.handleVariableProductPage();
			} else {
				let products = this.getCurrentPageProducts()

				if (products.length) {
					this.initialize(products)
				}

				this.listenToProductQuantityChanges();
			}

			// When customer clicks the Wallet button on a product page, the product is not in the cart, yet. Since there
			// is no "wallet open" event, we hook into the payment method change event to add the product to the cart. This
			// is the earliest we can do this, and an event that is guaranteed to fire after the payment sheet opens.
			this.uiElement.on('mwc_payments_poynt_wallet_payment_method_changed', (event, data) => {
				this.handleProductPageAddToCart(data);

				return true;
			});
		}

		/**
		 * Listens and handles changes to product quantities
		 */
		listenToProductQuantityChanges() {
			this.uiElement.on('focus.mwc_payments_poynt_wallets', '[name^="quantity"]', (event) => {
				$(event.target).data('previous-quantity', $(event.target).val());
			})

			this.uiElement.on('change.mwc_payments_poynt_wallets', '[name^="quantity"]', (event) => {

				let previousQuantity = $(event.target).data('previous-quantity');
				let newQuantity = $(event.target).val();

				// only refresh or tear down the button if a quantity was changed to or from zero
				if (newQuantity > 0 && previousQuantity > 0) {
					return;
				}

				$(event.target).data('previous-quantity', newQuantity);

				let products = this.getCurrentPageProducts()

				products.length ? this.reInitialize(products) : this.tearDown()
			})

		}

		/**
		 * Stops listening to changes to product quantities
		 */
		stopListeningToProductQuantityChanges() {
			this.uiElement.off('change.mwc_payments_poynt_wallets', '[name^="quantity"]');
			this.uiElement.off('focus.mwc_payments_poynt_wallets', '[name^="quantity"]')
		}

		/**
		 * Handles setting up Wallet on the single product page for variable products.
		 */
		handleVariableProductPage() {
			this.uiElement.on('show_variation', (event, variation, purchasable) => {
				if (purchasable) {
					this.reInitialize(this.getCurrentPageProducts());
					this.listenToProductQuantityChanges()
				}
			});
			this.uiElement.on('hide_variation', () => {
				this.tearDown();
				this.stopListeningToProductQuantityChanges()
			});
		}

		/**
		 * Handles adding the current product to the cart on single product pages.
		 *
		 * @param {Object} event
		 */
		handleProductPageAddToCart(event) {

			this.updateCart({
				products: this.getCurrentPageProducts(),
			}).then(() => {
				this.debugLog('Cart updated')

				this.getPaymentRequest().then((paymentRequest) => {

					this.debugLog('Payment request updated', paymentRequest)

					event.updateWith(paymentRequest);
				})
			}).catch(err => {
				this.debugLog('Failed to update cart', err)
				this.handleApiError(err, event)
			});
		}

		/**
		 * Gets a list of products with quantities on the current page, ready to be added to cart.
		 */
		getCurrentPageProducts() {
			let products = [];

			let addProduct = (productId, quantity) => {

				if (! productId || isNaN(quantity) || quantity <= 0) {
					return;
				}

				products.push({
					id: productId,
					quantity: quantity,
				});
			}

			// handle grouped quantity inputs
			if (this.uiElement.hasClass('grouped_form')) {

				this.uiElement.find('input[name^="quantity"]').each((event, element) => {
					addProduct(
						parseInt($(element).attr('name').match(/[0-9]+/)),
						parseFloat($(element).val())
					)
				});

				// handle simple & variable products
			} else {

				addProduct(
					parseInt(this.uiElement.find('input[name="variation_id"]').val() || this.uiElement.find('button[name="add-to-cart"]').val()),
					parseFloat(this.uiElement.find('input[name="quantity"]').val())
				)
			}

			return products
		}

		/**
		 * Handles setting up Wallet on the cart page.
		 */
		handleCartPage() {
			this.debugLog('Initializing the cart page');

			this.initialize();

			$( document.body ).on('updated_cart_totals', () => this.reInitialize());
		}

		/**
		 * Handles setting up Wallet on the checkout page.
		 */
		handleCheckoutPage() {
			this.debugLog('Initializing the checkout page');

			$( document.body ).on('updated_checkout', () => this.reInitialize());
		}

		/**
		 * Initializes Wallet.
		 */
		initialize(products = null) {

			if (this.initializing) {
				return;
			}

			this.initializing = true;

			this.getPaymentRequest(products).then((paymentRequest) => {

				this.collect = new TokenizeJs(this.businessId, this.appId, paymentRequest);

				this.collect.supportWalletPayments().then(result => {

					const supportedWallets = Object.keys(this.enabledButtons).filter(walletId => result[this.convertStringToCamelCase(walletId)])
					const unSupportedWallets = Object.keys(this.enabledButtons).filter(walletId => !result[this.convertStringToCamelCase(walletId)])

					if (supportedWallets.length) {

						this.debugLog(`${supportedWallets.map(this.convertSnakeCaseToCapitalizedString).join(', ')} supported, mounting...`);

						this.collect.mount('mwc-payments-wallet-buttons', document, {
							paymentMethods: supportedWallets,
							applePayButtonOptions: this.enabledButtons['apple_pay'] ?? {},
							googlePayButtonOptions: this.enabledButtons['google_pay'] ?? {},
						});
					}

					if (unSupportedWallets.length) {

						this.debugLog(`${unSupportedWallets.map(this.convertSnakeCaseToCapitalizedString).join(', ')} not supported.`);

						if (!supportedWallets.length) {
							this.hideUI();
						}
					}

				});

				this.initializeListeners();

			}).catch((data) => {

				this.debugLog('Could not load payment request', data);

				this.initializing = false;
			});
		}

		/**
		 * Initializes all of the event listeners.
		 */
		initializeListeners() {

			// fires when Wallet is ready
			this.collect.on('ready', event => {
				this.handleReady(event);
			} );

			// fires when the Wallet shipping address has been changed
			this.collect.on('shipping_address_change', event => {
				this.handleShippingAddressChanged(event);
			} );

			// fires when the Wallet payment method has been changed
			this.collect.on('payment_method_change', event => {
				this.handlePaymentMethodChange(event);
			} );

			// fires when the Wallet shipping method has been changed
			this.collect.on('shipping_method_change', event => {
				this.handleShippingMethodChange(event);
			} );

			// fires when the Wallet coupon code has been changed
			this.collect.on('coupon_code_change', event => {
				this.handleCouponCodeChange(event);
			} );

			// fires when Wallet has been authorized
			this.collect.on('payment_authorized', event => {
				this.handlePaymentAuthorized(event);
			} );

			// fires when there is an error
			this.collect.on('error', error => {
				this.handleError(error);
			} );

			// fires when the wallet is closed
			this.collect.on('close_wallet', () => {
				this.debugLog('Wallet closed')
			} );
		}

		/**
		 * Tears down Wallet.
		 */
		tearDown() {

			if (this.initialized) {
				this.collect.unmount('mwc-payments-wallet-buttons', document);
				this.initialized = false;
			}

			this.hideUI();
		}

		/**
		 * Re-initializes Wallet.
		 */
		reInitialize(products = null) {

			this.tearDown();

			if (this.businessId && this.appId && ! this.initializing) {
				this.initialize(products);
			}
		}

		/**
		 * Handles the "ready" event.
		 *
		 * @param {Object} event
		 */
		handleReady(event) {
			this.initializing = false;
			this.initialized  = true;

			this.debugLog('Wallet is ready', event);

			this.showUI();
		}

		/**
		 * Handles the "shipping_address_change" event.
		 *
		 * @param {Object} event
		 */
		handleShippingAddressChanged(event) {
			this.debugLog('The shipping address has been changed', event);

			this.updateCart({
				customer: {
					shippingAddress: this.getAdaptedAddress(event.shippingAddress)
				}
			}).then(() => {
				this.debugLog('Cart updated')

				this.getPaymentRequest().then((paymentRequest) => {

					this.debugLog('Payment request updated', paymentRequest)

					event.updateWith(paymentRequest)
				})
			}).catch(err => {
				this.debugLog('Failed to update cart', err)
				this.handleApiError(err, event);
			})
		}

		/**
		 * Handles API errors.
		 *
		 * @param {*} err
		 * @param {Object} event
		 */
		handleApiError(err, event) {

			const data = {
				error: {
					message: err.message
				}
			}
			const errCode = err.code.toLowerCase() // error codes must use lowercase snake_case

			// Pass errors to Apple Pay or Google Pay if possible
			if ([
				"invalid_shipping_address",
				"invalid_billing_address",
				"invalid_coupon_code",
				"expired_coupon_code",
				"unserviceable_address",
				"unknown"
			].indexOf(errCode) >= 0) {
				data.error.code = errCode

				if (err.data?.field && ['INVALID_BILLING_ADDRESS', 'INVALID_SHIPPING_ADDRESS'].indexOf(err.code) > -1) {
					// contrary to the error.code field above, Poynt expects the contactField in camelCase
					data.error.contactField = this.convertStringToCamelCase(err.data.field)
				}

				// otherwise, render a generic error and pass unknown error to Apple Pay or Google Pay
			} else {
				this.collect.abortApplePaySession()

				this.renderErrors([err.message])

				return;
			}

			event.complete ? event.complete(data) : event.updateWith(data)
		}

		/**
		 * Handles the "payment_method_change" event.
		 *
		 * @param {Object} event
		 */
		handlePaymentMethodChange(event) {
			this.debugLog('The payment method has been changed', event);

			// Allow actors to update the event with their own data. Note that single product pages listen to this event
			// to add the product to cart.
			if (! this.uiElement.triggerHandler('mwc_payments_poynt_wallet_payment_method_changed', event)) {
				event.updateWith({});
			}
		}

		/**
		 * Handles the "shipping_method_change" event.
		 *
		 * @param {Object} event
		 */
		handleShippingMethodChange(event) {
			this.debugLog('The shipping method has been changed', event);

			this.updateCart({
				customer: {
					shippingMethod: event.shippingMethod.id
				}
			}).then(() => {
				this.debugLog('Cart updated')

				this.getPaymentRequest().then((paymentRequest) => {

					this.debugLog('Payment request updated', paymentRequest)

					event.updateWith(paymentRequest)
				})
			}).catch(err => {
				this.debugLog('Failed to update cart', err)
				this.handleApiError(err, event)
			})
		}

		/**
		 * Handles the "coupon_code_change" event.
		 *
		 * @param {Object} event
		 */
		handleCouponCodeChange(event) {
			this.debugLog('The coupon code has been changed', event);

			this.updateCart({
				couponCode: event.couponCode
			}).then(() => {
				this.debugLog('Cart updated')

				this.getPaymentRequest().then((paymentRequest) => {

					this.debugLog('Payment request updated', paymentRequest)

					event.updateWith(paymentRequest)
				})
			}).catch(err => {
				this.debugLog('Failed to update cart', err)
				this.handleApiError(err, event)
			})
		}

		/**
		 * Handles the "payment_authorized" event.
		 *
		 * @param {Object} event
		 */
		handlePaymentAuthorized(event) {
			this.debugLog('Payment has been authorized', event);

			let data = {
				billingAddress: this.getAdaptedAddress(event.billingAddress),
				shippingAddress: this.getAdaptedAddress(event.shippingAddress),
			};

			if (event.shippingAddress?.emailAddress) {
				data.emailAddress = event.shippingAddress.emailAddress;
			}

			// Poynt provides the phone number as a part of shipping address, whereas WooCommerce expects it as part of
			// billing address, so we copy it from shippingAddress to billingAddress
			if (data.shippingAddress?.phone && !data.billingAddress?.phone) {
				data.billingAddress.phone = data.shippingAddress.phone
			}

			this.updateCart({
				customer: data
			}).then(() => {
				this.debugLog('Cart updated')

				this.makeApiRequest('POST', 'payments/godaddy-payments/wallets/processing/pay', {
					nonce: event.nonce,
					source: event.source,
				}).then(res => {
					this.debugLog('Payment created', res)

					event.complete();

					window.location.replace(res.redirectUrl)
				}).catch(err => {
					this.debugLog('Failed to create payment', err)
					this.handleApiError(err, event)
				})
			}).catch(err => {
				this.debugLog('Failed to update cart', err)
				this.handleApiError(err, event)
			})
		}

		/**
		 * Handles the error event data.
		 *
		 * Logs errors to console and maybe renders them in a user-facing notice.
		 *
		 * @param {Object} event after a form error
		 */
		handleError(event) {

			this.debugLog('Wallet error', event);

			let errorMessage = poyntPaymentFormI18n.errorMessages.genericError;

			// Poynt Collect API has some inconsistency about error message response data:
			if ( 'error' === event.type && event.data ) {
				if ( event.data.error && event.data.error.message && event.data.error.message.message ) {
					errorMessage = event.data.error.message.message;
				} else if ( event.data.message ) {
					errorMessage = event.data.message;
				} else if ( event.data.error && event.data.error.message && event.data.error.source && 'submit' === event.data.error.source ) {
					errorMessage = event.data.error.message;
				} else if ( event.data.error ) {
					errorMessage = event.data.error;
				}
			}

			if (errorMessage.includes('Request failed')) {
				errorMessage = poyntPaymentFormI18n.errorMessages.genericError;
			}

			this.renderErrors([errorMessage])
		}

		/**
		 * Logs an item to console if logging is enabled.
		 *
		 * @param {String} message
		 * @param {Object|null} data
		 */
		debugLog(message, data = null) {

			if (! this.isLoggingEnabled) {
				return;
			}

			console.log('[Wallets] '+message);

			if (null !== data) {
				console.log(data);
			}
		}

		/**
		 * Renders errors to the customer.
		 *
		 * @param errors
		 */
		renderErrors(errors) {
			$( '.woocommerce-error, .woocommerce-message' ).remove();

			this.uiElement.prepend('<ul class="woocommerce-error"><li>' + errors.join( '</li><li>' ) + '</li></ul>');
			this.uiElement.removeClass( 'processing' ).unblock();

			$('html, body').animate({scrollTop: this.uiElement.offset().top - 100}, 1000);
		}

		/**
		 * Hides the Wallet UI.
		 */
		hideUI() {
			// NOTE: add more advanced handling when multiple buttons are supported
			$('.mwc-external-checkout-buttons, .mwc-external-checkout-buttons-divider').hide().removeClass('available');
		}

		/**
		 * Shows the Wallet UI.
		 */
		showUI() {
			// NOTE: add more advanced handling when multiple buttons are supported
			$('.mwc-external-checkout-buttons, .mwc-external-checkout-buttons-divider').show().css('display', 'block').addClass('available');
		}

		/**
		 * Gets the initial payment request.
		 *
		 * @returns Promise
		 */
		getPaymentRequest(products = null) {
			return this.makeApiRequest('GET', 'payments/godaddy-payments/wallets/request', products ? { products } : null);
		}

		/**
		 * Updates the WooCommerce cart with the given data.
		 *
		 * @returns Promise
		 */
		updateCart(data) {
			return this.makeApiRequest('PATCH', 'cart', data);
		}

		/**
		 * Makes a request to the site REST API.
		 *
		 * @param {String} method
		 * @param {String} route
		 * @param {Object} data
		 *
		 * @return Promise
		 */
		makeApiRequest(method = 'GET', route, data = {}) {

			return new Promise((resolve, reject) => {
				$.ajax({
					url: this.apiUrl+'godaddy/mwc/v1/'+route,
					dataType: 'json',
					method: method,
					headers: {
						'X-MWC-Payments-Nonce': this.apiNonce
					},
					data: data
				}).done((data) => {
					resolve(data);
				}).fail((jqXHR) => {
					reject(jqXHR.responseJSON);
				});
			});
		}


		/**
		 * Converts a string to camelCase
		 *
		 * @param {String} str
		 *
		 * @return String
		 */
		convertStringToCamelCase = str => {
			return str.toLowerCase().replace(/[-_][a-z0-9]/g, group =>
				group.toUpperCase()
					.replace('-', '')
					.replace('_', '')
			);
		};


		/**
		 * Converts snake_case to capitalized string
		 *
		 * @param {String} str
		 *
		 * @return String
		 */
		convertSnakeCaseToCapitalizedString = str => {
			return str.split('_').map((word) => {
				return word[0].toUpperCase() + word.substring(1);
			}).join(' ');
		};


		/**
		 * Converts the address provided by Wallet to the format required by GDP.
		 *
		 * @param {Object} address
		 * @see https://developer.apple.com/documentation/apple_pay_on_the_web/applepaypaymentcontact
		 *
		 * @return Object
		 */
		getAdaptedAddress(address) {
			return address ? {
				countryCode: address.countryCode,
				locality: address.locality,
				postalCode: address.postalCode,
				firstName: address.givenName ?? address.name?.split(' ')[0],
				lastName: address.familyName ?? address.name?.split(' ').slice(1).join(' '),
				lines: address.addressLines,
				phone: address.phoneNumber,
				administrativeDistricts: [address.administrativeArea, address.subAdministrativeArea],
			} : null
		}
	}

	// dispatch loaded event
	$( document.body ).trigger( 'mwc_payments_wallets_handler_loaded' );

} );
