<?php

use Hikvision\Device;
use Hikvision\Exception;
use Hikvision\Hook;
use Hikvision\HttpClient;

try {
    $device     = new Device('127.0.0.1', 80, 'username', 'password');
    $deviceInfo = $device->getInfo();
    $hook       = new Hook($device);

    $hookID   = 2;
    $hookData = [
        'id'                  => $hookID,
        'url'                 => '/callback/' . $deviceInfo['deviceID'],
        'ipAddress'           => '127.0.0.1',
        'portNo'              => 8888,
        'parameterFormatType' => 'JSON',
    ];

    // get capabilities
    $capabilities = $hook->getCapabilities();
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // set hook
    $response = $hook->save($hookID, $hookData);
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // get hook info
    $response = $hook->getInfo($hookID);
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // test hook
    $response = $hook->test($hookID, $hookData);
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // delete hook
    $response = $hook->delete($hookID);
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

} catch (Exception $e) {
    echo "Error: {$e->getMessage()}";
}
