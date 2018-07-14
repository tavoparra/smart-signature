<?php
namespace Middleware;

use SmartSignature\User;

class TokenAuth {
    private $whiteList = null;
    private $c = null;
    private $loggedUser = null;

    public function __construct($container) {
        //Define the urls that you want to exclude from Authentication, aka public urls     
        $this->whiteList = ['\/login'];
        $this->c = $container;
    }

    /**
     * Deny Access
     *
     */
    public function deny_access($response) {
        $apiResponse = ['status' => 'fail', 'message' => 'Access denied'];
        return $response->withJson($apiResponse, 401);
    }

    /**
     * Check against the DB if the token is valid
     * 
     * @param string $token
     * @return bool
     */
    public function authenticate($token) {
        $this->loggedUser = User::where('token',$token)->first();

        if ($this->loggedUser) {
            $tokenExpire = $this->loggedUser->token_expire;
            $actualDateTime = date('Y-m-d H:i:s');

            return $tokenExpire > $actualDateTime;
        } else {
            return false;
        }

        return false;
    }

    /**
     * This function will compare the provided url against the whitelist and
     * return wether the $url is public or not
     * 
     * @param string $url
     * @return bool
     */
    public function isPublicUrl($url) {
        $patterns_flattened = implode('|', $this->whiteList);
        $matches = null;
        preg_match('/' . $patterns_flattened . '/', $url, $matches);
        return (count($matches) > 0);
    }

    /**
     * Call
     * 
     * @todo beautify this method ASAP!
     *
     */
    public function __invoke($request, $response, $next) {
        //We can check if the url requested is public or protected
        if ($this->isPublicUrl($request->getUri()->getPath())) {
            //if public, then we just call the next middleware and continue execution normally
            $response = $next($request, $response);
            return $response;
        } else {
            //Get the token sent from the request
            $tokenAuth = $request->getHeaders()['HTTP_AUTHORIZATION'][0];
            //If protected url, we check if our token is valid
            if ($this->authenticate($tokenAuth)) {
                //Get the user and make it available for the controller
                $this->c['loggedUser'] = $this->loggedUser;

                //Continue with execution
                $response = $next($request, $response);
                return $response;
            return $response;
            } else {
                return $this->deny_access($response);
            }
        }
    }

}
