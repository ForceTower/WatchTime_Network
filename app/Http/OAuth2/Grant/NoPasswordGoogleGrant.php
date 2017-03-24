<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 04/03/2017
 * Time: 00:52
 */

namespace WatchTime\Http\OAuth2\Grant;

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Event\ClientAuthenticationFailedEvent;
use League\OAuth2\Server\Event\UserAuthenticationFailedEvent;
use League\OAuth2\Server\Exception\InvalidClientException;
use League\OAuth2\Server\Exception\InvalidCredentialsException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Exception\ServerErrorException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Util\SecureKey;

class NoPasswordGoogleGrant extends AbstractGrant
{

    protected $identifier = 'no_password_google';

    public function setVerifyCredentialsCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    protected function getVerifyCredentialsCallback()
    {
        if (is_null($this->callback) || !is_callable($this->callback)) {
            throw new ServerErrorException('Null or non-callable callback set on Password grant');
        }

        return $this->callback;
    }

    public function completeFlow() {
        $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
        if (is_null($clientId)) {
            throw new InvalidRequestException('client_id');
        }

        $clientSecret = $this->server->getRequest()->request->get('client_secret',
            $this->server->getRequest()->getPassword());
        if (is_null($clientSecret)) {
            throw new InvalidRequestException('client_secret');
        }

        $client = $this->server->getClientStorage()->get(
            $clientId,
            $clientSecret,
            null,
            $this->getIdentifier()
        );

        if (($client instanceof ClientEntity) === false) {
            $this->server->getEventEmitter()->emit(new ClientAuthenticationFailedEvent($this->server->getRequest()));
            throw new InvalidClientException();
        }

        $email = $this->server->getRequest()->request->get('email', null);
        if (is_null($email)) {
            throw new InvalidRequestException('email');
        }

        $google_id = $this->server->getRequest()->request->get('google_id', null);
        if (is_null($google_id)) {
            throw new InvalidRequestException('google_id');
        }

        $google_tk = $this->server->getRequest()->request->get('google_token', null);
        if (is_null($google_tk)) {
            throw new InvalidRequestException('google_token');
        }

        $userId = call_user_func($this->getVerifyCredentialsCallback(), $email, $google_id, $google_tk);

        if ($userId === false) {
            $this->server->getEventEmitter()->emit(new UserAuthenticationFailedEvent($this->server->getRequest()));
            throw new InvalidCredentialsException();
        }

        // Validate any scopes that are in the request
        $scopeParam = $this->server->getRequest()->request->get('scope', '');
        $scopes = $this->validateScopes($scopeParam, $client);

        // Create a new session
        $session = new SessionEntity($this->server);
        $session->setOwner('user', $userId);
        $session->associateClient($client);

        // Generate an access token
        $accessToken = new AccessTokenEntity($this->server);
        $accessToken->setId(SecureKey::generate());
        $accessToken->setExpireTime($this->getAccessTokenTTL() + time());

        // Associate scopes with the session and access token
        foreach ($scopes as $scope) {
            $session->associateScope($scope);
        }

        foreach ($session->getScopes() as $scope) {
            $accessToken->associateScope($scope);
        }

        $this->server->getTokenType()->setSession($session);
        $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
        $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());

        // Associate a refresh token if set
        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken = new RefreshTokenEntity($this->server);
            $refreshToken->setId(SecureKey::generate());
            $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
            $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());
        }

        // Save everything
        $session->save();
        $accessToken->setSession($session);
        $accessToken->save();

        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken->setAccessToken($accessToken);
            $refreshToken->save();
        }

        return $this->server->getTokenType()->generateResponse();
    }
}