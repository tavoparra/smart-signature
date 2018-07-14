<?php

namespace SmartSignature;

class SessionController {
    public function login($request, $response) {
        $apiResponse = [];

        $username = $request->getParam('username');
        $password = $request->getParam('password');

        if(!empty($username) && !empty($password)) {
            $user = User::where('username',$username)->first();
            if($user && $user->password === $password) {
                $token = $this->generateToken();
                $user->token = $token;
                $user->token_expire = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $user->save();

                $code = 200;
                $apiResponse['user'] = $user->name;
                $apiResponse['token'] = $token;
            } else {
                $code = 401;
                $apiResponse['status'] = 'fail';
                $apiResponse['message'] = 'Access denied';
            }
        } else {
            $code = 401;
            $apiResponse['status'] = 'fail';
            $apiResponse['message'] = 'Please enter required details';
        }
        
        return $response->withJson($apiResponse, $code);
    }


    private function generateToken() {
        return bin2hex(openssl_random_pseudo_bytes(8));
    }
}
