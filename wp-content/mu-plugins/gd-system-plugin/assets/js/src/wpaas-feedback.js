/** global wpaasFeedback */

import domReady from '@wordpress/dom-ready';
import apiFetch from '@wordpress/api-fetch';
import { render, useState, unmountComponentAtNode, useEffect } from '@wordpress/element';
import { close } from '@wordpress/icons';
import { Icon, RadioControl, Button } from '@wordpress/components';

import { ReactComponent as GoDaddyLogo } from './go-daddy-logo.svg';
import { logImpressionEvent, logInteractionEvent } from './instrumentation';

const surveyChoices = Array.from({ length: wpaasFeedback?.score_choices.max + 1 }, ( v, k ) => k + wpaasFeedback?.score_choices.min )
							.map( ( choice ) => ( { label: choice, value: choice } ) );

const surveyLabels = wpaasFeedback?.labels;

const EID_PREFIX = `wp.${ wp.editPost ? 'editor' : 'wpadmin' }`;

const startedAt = new Date().toISOString();
const DISMISS_KEY = 'wpaas-nux-feedback-dismiss';
//               hour  min  sec  ms
const daysInMs = 24 * 60 * 60 * 1000;

const browserDismiss = (days = 90) => {
	localStorage?.setItem( DISMISS_KEY, ( Date.now() + ( days * daysInMs ) ) )
}

const Feedback = () => {
	const [ surveyScore, setSurveyScore ] = useState( null );
	const [ surveyComment, setSurveyComment ] = useState( '' );
	const [ dismissSurvey, setDismissSurvey ] = useState( false );

	const [ showSuccess, setShowSuccess ] = useState( false );

	useEffect( () => {
		logImpressionEvent(`${ EID_PREFIX }.feedback/wpaas-nps.modal`);
	}, [] );

	useEffect( () => {
		if ( dismissSurvey ) {
			unmountComponentAtNode( wpaasFeedback.rootNode.getElementById( wpaasFeedback.mountPoint ) );
		}
	}, [ dismissSurvey ] );

	if ( ! surveyLabels ) {
		return null;
	}

	const handleDismissModal = () => {
		if ( ! showSuccess ) {
			apiFetch( {
				url: wpaasFeedback.apiBase + '/dismiss',
				method: 'POST'
			} ).catch((error) => {
				// Log error to traffic here
				logInteractionEvent({
					eid: `${ EID_PREFIX }.feedback/wpaas-nps/error/dismiss.modal`,
					type: 'custom',
					data: {
						message: error?.message
					}
				});
				browserDismiss();
			} );
		}

		setDismissSurvey( true );
	}

	const handleSubmitModal = () => {
		setShowSuccess( true );

		apiFetch( {
			url: wpaasFeedback.apiBase + '/score',
			method: 'POST',
			data: {
				'comment': surveyComment,
				'endedAt': new Date().toISOString(),
				'isWpAdmin' : wpaasFeedback.isWpAdmin,
				'score': surveyScore,
				startedAt,
				'wpUri': String( window.location.href ).replace( window.location.origin, '' ),
			}
		} ).catch((error) => {
			// Log error to traffic here
			logInteractionEvent({
				eid: `${ EID_PREFIX }.feedback/wpaas-nps/error/score.modal`,
				type: 'custom',
				data: {
					message: error?.message
				}
			});
			browserDismiss();
		} );
	}

	const surveyCommentMaxLength = wpaasFeedback?.comment_length;

	return (
		<div className="wpaas-feedback-modal__container">
			<div className="wpaas-feedback-modal__header">
				<Icon data-eid={ `${ EID_PREFIX }.feedback/wpaas-nps/modal.close.click` } className="wpaas-feedback-modal__header__close" onClick={ handleDismissModal } icon={ close } />
				{ !showSuccess && (
					<GoDaddyLogo />
				)}
			</div>
			<div className="wpaas-feedback-modal__content">
				{ showSuccess ? (
					<>
						<div className="wpaas-feedback__success">
							<GoDaddyLogo />
							<h4 className="wpaas-feedback__success__header">{ surveyLabels?.thank_you }</h4>
							<Button disabled={ !surveyScore ? true : false } onClick={ handleDismissModal } isPrimary>
								{ surveyLabels?.thank_you_button }
							</Button>
						</div>
					</>
				) : (
					<>
						<div className="wpaas-feedback__question-container">
							<label className="wpaas-feedback__question-label">{ surveyLabels?.survey_question }</label>
							<RadioControl
									selected={ surveyScore }
									options={ surveyChoices }
									onChange={ ( value ) => setSurveyScore( Number( value ) ) }
								/>
								<div className="wpaas-feedback__survey-question__labels">
									<p>{ surveyLabels?.not_likely }</p>
									<p>{ surveyLabels?.neutral }</p>
									<p>{ surveyLabels?.likely } </p>
								</div>
						</div>
						<div className="wpaas-feedback__question-container">
							<label className="wpaas-feedback__question-label">{ surveyLabels?.comment_text }</label>
							<div className="wpaas-feedback__textarea__container">
								<textarea
									value={ surveyComment }
									maxLength={ surveyCommentMaxLength }
									onChange={ e => setSurveyComment( e.target.value )}
								/>
								<p className={`wpaas-feedback__textarea__count${ surveyComment.length === surveyCommentMaxLength ? '-bold' : '' }`}>{ surveyComment.length } / { surveyCommentMaxLength }</p>
							</div>
						</div>
						<span>
							<span dangerouslySetInnerHTML={{ __html: surveyLabels?.privacy_disclaimer }} />
						</span>
						<div className="wpaas-feedback__submit-form">
							<Button
								data-eid={ `${ EID_PREFIX }.feedback/wpaas-nps/form/submit.button.click` }
								disabled={ surveyScore === null }
								onClick={ handleSubmitModal }
								isPrimary
							>
								{ surveyLabels?.submit_feedback }
							</Button>
						</div>
					</>
				)}
			</div>
		</div>
	);
};

/**
 * customElements need ES5 classes but babel compiles them which errors out. We could use a polyfill or the below.
 * This is needed to circumvent babel crosscompiling.
 */
function BabelHTMLElement() {
	return Reflect.construct( HTMLElement, [], this.__proto__.constructor );
}
Object.setPrototypeOf( BabelHTMLElement, HTMLElement );
Object.setPrototypeOf( BabelHTMLElement.prototype, HTMLElement.prototype );

/**
 * See https://reactjs.org/docs/web-components.html#using-react-in-your-web-components
 */
class GoDaddyFeedback extends BabelHTMLElement {

	connectedCallback() {
		const mountPoint = document.createElement( 'div' );
		mountPoint.id = 'wpaas-feedback';

		function createStyle( url ) {
			const style = document.createElement( 'link' );
			style.setAttribute( 'rel', 'stylesheet' );
			style.setAttribute( 'href', url );
			style.setAttribute( 'media', 'all' );

			return style;
		}

		const shadowRoot = this.attachShadow( { mode: 'open' } );
		shadowRoot.appendChild( createStyle( wpaasFeedback.css ) );
		shadowRoot.appendChild( mountPoint );
		wpaasFeedback.rootNode = shadowRoot;
		wpaasFeedback.mountPoint = mountPoint.id;

		render(
			<Feedback />,
			mountPoint
		);
	}

}

function loadNpsComponent() {
	// customElements always need hyphen in the name.
	const customElementName = wpaasFeedback.containerId;

	customElements.define( customElementName, GoDaddyFeedback );

	const element = document.createElement( customElementName );

	// The PHP script actually prints the following tag in the dom <div id="wpaas-feedback-container">.
	// We replace with the custom element the div printed by PHP.
	document.getElementById( customElementName ).replaceWith( element );
}

domReady( () => {
	const userDismiss = localStorage?.getItem( DISMISS_KEY );

	if ( userDismiss && userDismiss > Date.now() ) {
		return;
	}

	apiFetch( {
		url: wpaasFeedback.apiBase + '/healthcheck',
		method: 'POST'
	} )
	.then(loadNpsComponent)
	.catch((error) => {
		// Log error to traffic here
		logInteractionEvent({
			eid: `${ EID_PREFIX }.feedback/wpaas-nps/error/healthcheck.modal`,
			type: 'custom',
			data: {
				message: error?.message
			}
		});
		browserDismiss(1);
	} );
} );
