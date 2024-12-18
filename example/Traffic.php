<?php

use Hikvision\Device;
use Hikvision\Exception;
use Hikvision\HttpClient;
use Hikvision\Traffic;

try {
    $device     = new Device('127.0.0.1', 80, 'username', 'password');
    $deviceInfo = $device->getInfo();
    $traffic    = new Traffic($device);

    // Fetches the capabilities of the system's I/O outputs
    $capabilities = $traffic->getCapabilities();
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // Retrieves the current vehicle detection configuration
    $response = $hook->getVehicleDetectionConfig();
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

    // Retrieves the current license plate image
    $picTime  = new DateTime('2024-12-16 00:00:00');
    $response = $traffic->capturePlateImage($picTime);
    if ($response['code'] != HttpClient::CODE_ERROR) {
        print_r($response);
    } else {
        throw new Exception($response['message']);
    }

} catch (Exception $e) {
    echo "Error: {$e->getMessage()}";
}
