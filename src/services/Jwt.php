<?php

namespace Feel\Services;

class Jwt
{
    public function create($id)
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $header = json_encode($header);
        $header = base64_encode($header);

        $payload = [
            'id' => $id
        ];
        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$header.$payload", JWT_TOKEN, true);
        $signature = base64_encode($signature);

        $token = "$header.$payload.$signature";

        return $token;
    }

    public function verify($token)
    {
        $part = explode('.', $token);
        $header = $part[0];
        $payload = $part[1];
        $signature = $part[2];

        $valid = hash_hmac('sha256', "$header.$payload", JWT_TOKEN, true);
        $valid = base64_encode($valid);

        if($valid !== $signature) {
            echo json_encode(['message' => 'Token informado invalido']);
            return http_response_code(401);
        }

        $payload = base64_decode($payload);

        return json_decode($payload, true);
    }
}