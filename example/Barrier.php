<?php

use Hikvision\Barrier;
use Hikvision\Device;
use Hikvision\Exception;
use Hikvision\HttpClient;

try {
    $device     = new Device('127.0.0.1', 80, 'username', 'password');
    $deviceInfo = $device->getInfo();

    $barrier = new Barrier($device);

    // get capabilities
    $response = $barrier->getCapabilities();
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // get config
    $response = $barrier->getConfig();
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // get barrier status
    $response = $barrier->getStatus();
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // trigger action
    $response = $barrier->trigger(Barrier::ACTION_OPEN);
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

} catch (Exception $e) {
    echo "Error: {$e->getMessage()}";
}
