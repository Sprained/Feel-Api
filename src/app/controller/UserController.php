<?php

namespace Feel\App\Controller;

use Feel\Database\Psql;
use Sprained\Validator;
use Feel\App\Middlewares\Auth;

class UserController extends Psql
{
    public function register()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        $validator = new Validator();

        $name = $validator->required($body['name'], 'Nome');
        $email = $validator->email($body['email']);
        $password = $validator->password($body['password']);
        $validator->confirm_password($body['password'], $body['confirm_password']);

        $con = $this->connect();

        $psql = pg_prepare($con, 'select_user', "SELECT id FROM users WHERE email = $1");
        $psql = pg_execute('select_user', [$email]);
        $psql = pg_fetch_assoc($psql);

        if($psql) {
            echo json_encode(['message' => 'Usúario já cadastrado!']);
            return http_response_code(400);
        }

        $psql = pg_prepare($con, 'register_user', "INSERT INTO users (name, email, password) VALUES ($1, $2, $3)");
        $psql = pg_execute('register_user', [$name, $email, $password]);
        
        if(!$psql) {
            return http_response_code(500);
        }

        return http_response_code(201);
    }

    public function select()
    {
        $auth = new Auth();
        $jwt = $auth->jwt();
        
        $con = $this->connect();
        
        $psql = pg_prepare($con, 'select_user', "SELECT name, email, avatar FROM users WHERE id = $1");
        $psql = pg_execute('select_user', [$jwt['id']]);
        $psql = pg_fetch_assoc($psql);

        echo json_encode(
            ['name' => $psql['name'], 'email' => $psql['email'], 'url' => 'url/' . $psql['avatar']]
        );
    }
}