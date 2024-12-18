<?php
namespace Hikvision;

use DateTime;

class Traffic extends DeviceManager
{
    /**
     * Class Constants
     *
     * These constants represent types and actions that can be performed with a Hikvision device.
     */

    public $channelID = 1;

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
     * Fetches the capabilities of the system's I/O outputs.
     *
     * This function sends an HTTP GET request to the `/ISAPI/Traffic/capabilities` endpoint
     * to retrieve information about the system's input/output capabilities. The response is processed
     * and converted from XML to an associative array. If an error occurs, an error message is returned
     * along with the response data.
     *
     * @return array
     */
    public function getCapabilities()
    {
        $response = $this->httpClient->get('/ISAPI/Traffic/capabilities');
        $response = Helpers::xmlToArray($response);
        if ($errorMessage = HttpClient::getErrorMessage($response)) {
            return [
                'code'     => HttpClient::CODE_ERROR,
                'message'  => $errorMessage,
                'response' => $response,
            ];
        }

        return $response;
    }

    /**
     * Retrieves the current vehicle detection configuration.
     *
     * @return array
     */
    public function getVehicleDetectionConfig()
    {
        $response = $this->httpClient->get("/ISAPI/Traffic/channels/{$this->channelID}/vehicleDetect");
        $response = Helpers::xmlToArray($response);

        if ($errorMessage = HttpClient::getErrorMessage($response)) {
            return [
                'code'     => HttpClient::CODE_ERROR,
                'message'  => $errorMessage,
                'response' => $response,
            ];
        }

        return $response;
    }

    /**
     * Updates the vehicle detection configuration.
     *
     * @param array $config Configuration data in array format.
     *
     * @return array
     */
    public function setVehicleDetectionConfig(array $config)
    {
        $response = $this->httpClient->put("/ISAPI/Traffic/channels/{$this->channelID}/vehicleDetect", <<<XML
            <VehicleDetectCfg>
                <enabled>{$config['enable']}</enabled>
                <stateOrProvinceName>{$config['stateOrProvinceName']}</stateOrProvinceName>
                <VehicleDetectSceneList>
                    <VehicleDetectScene>
                        <id>1</id>
                        <sceneName>Entrance</sceneName>
                        <enabled>true</enabled>
                        <PlateRecogParam>
                            <PlateRecogRegionList>
                                <PlateRecogRegion>
                                    <id>1</id>
                                    <RegionCoordinatesList>
                                        <RegionCoordinates>
                                            <positionX>100</positionX>
                                            <positionY>200</positionY>
                                        </RegionCoordinates>
                                        <RegionCoordinates>
                                            <positionX>300</positionX>
                                            <positionY>400</positionY>
                                        </RegionCoordinates>
                                    </RegionCoordinatesList>
                                </PlateRecogRegion>
                            </PlateRecogRegionList>
                        </PlateRecogParam>
                    </VehicleDetectScene>
                </VehicleDetectSceneList>
            </VehicleDetectCfg>
        XML);

        $response = Helpers::xmlToArray($response);

        if ($errorMessage = HttpClient::getErrorMessage($response)) {
            return [
                'code'     => HttpClient::CODE_ERROR,
                'message'  => $errorMessage,
                'response' => $response,
            ];
        }

        return $response;
    }

    /**
     * Retrieves the current license plate image.
     *
     * @param null|DateTime $picTime
     * @return array
     */
    public function capturePlateImage($picTime = null)
    {
        $picTime  = $picTime instanceof DateTime ? $picTime->format('Y-m-d\TH:i:s') : null;
        $response = $this->httpClient->get("/ISAPI/Traffic/channels/{$this->channelID}/vehicleDetect/plates", <<<XML
            <AfterTime>
                <picTime>{$picTime}</picTime>
            </AfterTime>
        XML);

        $response = Helpers::xmlToArray($response);
        if ($errorMessage = HttpClient::getErrorMessage($response)) {
            return [
                'code'     => HttpClient::CODE_ERROR,
                'message'  => $errorMessage,
                'response' => $response,
            ];
        }

        return $response;
    }
}
