<?php

namespace SmartSignature;

class DocumentController {
    private $c = null;

    public function __construct($container) {
        $this->c = $container;
    }

    public function pending($request, $response) {
        $pendingDocs = $this->c['loggedUser']->authorizations()
                        ->where('status', 'PENDING')
                        ->with('owner:id,name')
                        ->get();

        return $response->withJson($pendingDocs, 200);
    }

    public function signed($request, $response) {
        $pendingDocs = $this->c['loggedUser']->authorizations()
                        ->where('status', 'SIGNED')
                        ->with('owner:id,name')
                        ->get();

        return $response->withJson($pendingDocs, 200);
    }

    public function documents($request, $response) {
        $pendingDocs = $this->c['loggedUser']->documents()
                        ->with('authorizer:id,name')
                        ->get();

        return $response->withJson($pendingDocs, 200);
    }

    public function sign($request, $response, $args = []) {
        $doc = Document::findOrFail($args['id']);
        $apiResponse = [];

        switch($doc->status) {
            case 'REJECTED':
                $code = 400;
                $apiResponse['status'] = 'fail';
                $apiResponse['message'] = 'Document is rejected already';
                break;
            case 'SIGNED':
                $code = 400;
                $apiResponse['status'] = 'fail';
                $apiResponse['message'] = 'Document is signed already';
                break;
            case 'PENDING':
                if($doc->authorizer_id !== $this->c['loggedUser']->id) {
                    $code = 401;
                    $apiResponse['status'] = 'fail';
                    $apiResponse['message'] = 'You are not authorized to sign this document';
                } else {
                    $doc->signature = $this->generateSignature($doc);
                    $doc->status = 'SIGNED';
                    $doc->save();
                    $apiResponse = $doc;
                    $code = 200;
                }
        }

        return $response->withJson($apiResponse, $code);
    }

    private function generateSignature($doc) {
        $signature = $doc->id.'|'.
                     $doc->authorizer->name.'|'.
                     date('D M d, Y G:i').'|'.
                     $this->generateAsymmetricKey();

       return $signature; 
    }

    private function generateAsymmetricKey() {
        // TODO: Implement real assymetric key generation
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
