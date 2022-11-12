<?php

namespace GoDaddy\WordPress\MWC\Core\Payments\Providers\MWC\Gateways;

use Exception;
use GoDaddy\WordPress\MWC\Common\Events\Events;
use GoDaddy\WordPress\MWC\Common\Http\Request;
use GoDaddy\WordPress\MWC\Common\Http\Response;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;
use GoDaddy\WordPress\MWC\Core\Payments\Exceptions\AutoConnectFailedException;
use GoDaddy\WordPress\MWC\Core\Payments\Exceptions\NoAccountFoundException;
use GoDaddy\WordPress\MWC\Core\Payments\Providers\MWC\Http\Adapters\FindOrCreateOnboardingAccountRequestAdapter;
use GoDaddy\WordPress\MWC\Payments\Events\ProviderRequestEvent;
use GoDaddy\WordPress\MWC\Payments\Events\ProviderResponseEvent;
use GoDaddy\WordPress\MWC\Payments\Gateways\AbstractGateway;

/**
 * Gateway for handling onboarding accounts via the MWC API.
 */
class OnboardingAccountGateway extends AbstractGateway
{
    use CanGetNewInstanceTrait;

    /**
     * Finds or creates GoDaddy Payments account details for the given values.
     *
     * @param string $serviceId
     * @param string $webhookSecret
     *
     * @return array
     * @throws Exception
     */
    public function findOrCreate(string $serviceId, string $webhookSecret) : array
    {
        return $this->doAdaptedRequest($serviceId, FindOrCreateOnboardingAccountRequestAdapter::getNewInstance($serviceId, $webhookSecret));
    }

    /**
     * Performs a request.
     *
     * @param Request $request request object
     *
     * @return Response
     * @throws Exception
     */
    public function doRequest(Request $request) : Response
    {
        // @TODO: Create trait and method for broadcasting events and sending requests MWC-8961 {@ssmith1 2022-10-27}
        Events::broadcast(new ProviderRequestEvent($request));

        /** @var Response $response */
        $response = $request->send();

        Events::broadcast(new ProviderResponseEvent($response));

        if ($response->isError()) {
            $errormessage = $response->getErrorMessage() ?? '';
            if ($response->getStatus() === 404) {
                throw new NoAccountFoundException($errormessage);
            } else {
                throw new AutoConnectFailedException($errormessage);
            }
        }

        return $response;
    }
}
