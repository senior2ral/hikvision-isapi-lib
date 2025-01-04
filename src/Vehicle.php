<?php
namespace Hikvision;

class Vehicle extends DeviceManager
{
    /**
     * @var HttpClient $httpClient
     *
     * The HTTP client instance used to communicate with the Hikvision device.
     *
     * This property holds an instance of the `HttpClient` class, which manages HTTP requests
     * and responses between the application and the Hikvision device. It is initialized
     * in the constructor and uses the device's IP address, port, username, and password
     * for authentication and communication.
     *
     * Scope: `protected`
     * - This means the property is accessible only within the class and its child classes.
     */
    protected $httpClient;

    /**
     * Constructor for initializing the device manager.
     *
     * This constructor initializes the device manager and sets up the HTTP client for communication
     * with the Hikvision device. It validates that the provided `$device` is an instance of the `Device`
     * class and throws an exception if it's not. Once validated, it initializes the HTTP client with
     * the device's connection details.
     *
     * @param Device $device The device instance containing connection details (IP, port, username, password).
     *
     * @throws Exception If the provided `$device` is not a valid instance of the `Device` class.
     */
    public function __construct(Device $device)
    {
        if (!$device instanceof Device) {
            throw new Exception('Unknown device manager');
        }

        $this->device     = $device;
        $this->httpClient = new HttpClient(
            $this->device->ip,
            $this->device->port,
            $this->device->username,
            $this->device->password
        );
    }

    /**
     * Gets the current vehicle recognition configuration from the Hikvision device.
     *
     * This method retrieves the current vehicle recognition settings, including whether the
     * feature is enabled and the current regex pattern for license plate recognition.
     *
     * @return array The current configuration response from the device (success or error).
     */
    public function getVehicleConfiguration()
    {
        // Sending GET request to the vehicle recognition configuration endpoint
        $response = $this->httpClient->get('/ISAPI/Intelligent/vehicleRecognition/configuration');

        // Converting XML response to array for easier handling
        $response = Helpers::xmlToArray($response);

        // Checking if there is an error message in the response
        if ($errorMessage = HttpClient::getErrorMessage($response)) {
            return [
                'code'     => HttpClient::CODE_ERROR,
                'message'  => $errorMessage,
                'response' => $response,
            ];
        }

        // Returning the current configuration response
        return $response;
    }

    /**
     * Sets the vehicle recognition configuration for the Hikvision device.
     *
     * This method allows you to configure the vehicle recognition settings, including enabling/disabling
     * the feature and setting the regex pattern for license plate recognition.
     * It sends the configuration as an XML payload to the Hikvision device.
     *
     * @param array $config The configuration array, including 'enable' and 'plateRecognition.regexPattern'.
     * @return array The response from the device (either success or error).
     */
    public function setVehicleConfiguration($config = [])
    {
        // Constructing the XML payload with the provided configuration
        $response = $this->httpClient->put('/ISAPI/Intelligent/vehicleRecognition/configuration', <<<XML
            <VehicleRecognitionConfiguration>
                <enabled>{$config['enable']}</enabled>
                <plateRecognition>
                    <regexPattern>{$config['plateRecognition']['regexPattern']}</regexPattern>
                </plateRecognition>
            </VehicleRecognitionConfiguration>
        XML);

        // Converting XML response to an array for easier handling
        $response = Helpers::xmlToArray($response);

        // Check if there is an error message in the response
        if ($errorMessage = HttpClient::getErrorMessage($response)) {
            return [
                'code'     => HttpClient::CODE_ERROR,
                'message'  => $errorMessage,
                'response' => $response,
            ];
        }

        // Return the successful response from the device
        return $response;
    }
}
