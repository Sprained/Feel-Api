<?php

namespace Feel\App\Middlewares;

use Feel\Services\Jwt;

class Auth
{
    public function jwt()
    {
        $headers = getallheaders();

        $headers = $headers['Authorization'];

        if(!$headers) {
            echo json_encode(['message' => 'Token não informado']);
            return http_response_code(401);
        }

        $jwt = new Jwt();

        return $jwt->verify($headers);
    }
}