<?php

namespace Frontier;

class Router
{
    use Traits\ApiRequests, Traits\PasswordSaltLogin;

    private $devices;
    private $networkInfo;

    function devices()
    {
        $this->login();

        $this->devices = $this->get("/api/devices")->json();

        $this->logout();

        return $this->devices;
    }

    function networkInfo(string $key = '', int $network = 1)
    {
        $this->login();

        $this->networkInfo = $this->get("/api/network/${network}")->json($key);

        $this->logout();

        return $this->networkInfo;
    }
}
