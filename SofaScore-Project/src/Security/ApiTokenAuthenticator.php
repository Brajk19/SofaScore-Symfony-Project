<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{

    //URLs that require token (JSON API routes)
    private array $paths = ["setMatch", "changeCompetition", "changeCompetitor", "changeSeason", "recentMatches",
                        "standings", "standingsInfo"];

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = ['message' => 'Authentication Required'];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        //token needs to be provided only on JSON API routes (JsonApiRoutesController)
        $pathInfo = substr($request->getPathInfo(), 1);
        $pathInfo = substr($pathInfo, 0, strpos($pathInfo, "/"));

        if(in_array($pathInfo, $this->paths)){
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        if($request->headers->has("X-AUTH-TOKEN")){
            return $request->headers->get("X-AUTH-TOKEN");
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (is_null($credentials)) {
            return null;
        }

        return $userProvider->loadUserByIdentifier($credentials);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, \Symfony\Component\Security\Core\User\UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = ['message' => strtr($exception->getMessageKey(), $exception->getMessageData()) ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, string $providerKey)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}