<?php

namespace Feel\App\Controller;

use Feel\Database\Psql;
use Feel\Services\Jwt;
use Sprained\Validator;

class SessionController extends Psql
{
    public function login()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        $validator = new Validator();

        $email = $validator->email($body['email']);
        $password = md5($validator->required($body['password'], 'Senha'));

        $con = $this->connect();

        $psql = pg_prepare($con, 'select-user', "SELECT id, password FROM users WHERE email = $1");
        $psql = pg_execute('select-user', [$email]);
        
        if(!$psql) {
            echo json_encode(['message' => 'Email ou senha informado com erro!']);
            return http_response_code(401);
        }

        $psql = pg_fetch_assoc($psql);

        if($psql['password'] !== $password) {
            echo json_encode(['message' => 'Email ou senha informado com erro!']);
            return http_response_code(401);
        }

        $token = new Jwt();

        $jwt = $token->create($psql['id']);
        
        echo json_encode(['token' => $jwt]);
        return http_response_code(200);
    }
}