<?php

namespace Frontier\Traits;

trait PasswordSaltLogin
{
    function login()
    {
        // Get passwordSalt GET /api/login -> passwordSalt
        $passwordSalt = $this->get("/api/login")->assertStatus(401)->json('passwordSalt');

        $password = hash("sha512", "{$this->config['password']}{$passwordSalt}");

        $response = $this->post("/api/login", [
            'password' => $password
        ]);

        if ($response->statusCode() != 200) {
            if ($response->json('denyState') == 1) {
                throw new \Exception('Login has been disabled for 1 minute, try again later');
            }
            if ($response->json('denyState') == 2) {
                throw new \Exception('Login has been disabled for 24 hours, wait 24 hours or reset password');
            }
            if ($response->json('error') == 2) {
                throw new \Exception('Max sessions limit reached, wait until open sessions expire');
            }
            throw new \Exception('Invalid login, double check password');
        }
    }

    function logout()
    {
        $this->get('/api/logout')->assertStatus(200);
    }
}
