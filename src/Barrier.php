<?php
namespace Hikvision;

class Barrier extends DeviceManager
{
    /**
     * Class Constants
     *
     * These constants represent types and actions that can be performed with a Hikvision device.
     */

    # Types of Outputs
    const TYPE_SIGNAL = 'signal';
    const TYPE_GATE   = 'gate';

    # Actions for Outputs
    const ACTION_OPEN  = 'open';
    const ACTION_CLOSE = 'close';

    const USE_TYPE_DISABLE       = 'disable';
    const USE_TYPE_ELECTRIC_LOCK = 'electricLock';
    const USE_TYPE_CUSTOM        = 'custom';

    /**
     * Output ID for the device or system.
     *
     * This property stores the ID of the output port or device action that the system interacts with.
     * By default, the output ID is set to 2, which can be modified depending on the device's configuration
     * or the desired output to trigger.
     */
    public $portID = 2;

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
     * This function sends an HTTP GET request to the `/ISAPI/System/IO/outputs/capabilities` endpoint
     * to retrieve information about the system's input/output capabilities. The response is processed
     * and converted from XML to an associative array. If an error occurs, an error message is returned
     * along with the response data.
     *
     * @return array
     */
    public function getCapabilities()
    {
        $response = $this->httpClient->get('/ISAPI/System/IO/outputs/capabilities');
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
     * Retrieves the configuration of a specified output port from the device.
     *
     * This method sends a GET request to the Hikvision device API to fetch the configuration of
     * a specific output port. The response is converted from XML to an associative array for
     * easier manipulation. Any errors in the response are checked and returned as part of the result.
     *
     * @return array
     */
    public function getConfig()
    {
        $response = $this->httpClient->get("/ISAPI/System/IO/outputs/{$this->portID}");
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
     * Configures the output port with specified parameters.
     *
     * This method configures the output port on the Hikvision device, including settings such as
     * the default state, output state, pulse duration, port name, I/O use type, and normal status.
     * It sends a PUT request to the device's API and processes the response.
     *
     * @param string $defaultState The default state of the output port on power-up.
     *                             Valid options: Barrier::ACTION_OPEN, Barrier::ACTION_CLOSE.
     * @param string $outputState The state of the output port when triggered.
     *                            Valid options: Barrier::ACTION_OPEN, Barrier::ACTION_CLOSE.
     * @param int $pulseDuration The duration of the pulse signal (in milliseconds).
     *                           Only valid when $outputState is "pulse". Default is 1000 ms.
     * @param string|null $name The name of the output port (optional).
     * @param string|null $ioUseType The I/O use type (optional). Valid options: "disable", "electricLock", "custom".
     * @param string|null $normalStatus The normal status of the output port (optional). Valid options: "open", "close".
     *
     * @return array
     *
     * @throws Exception If any of the parameters are invalid.
     */
    public function setConfig($defaultState, $outputState, $pulseDuration = 1000, $name = null, $ioUseType = null, $normalStatus = null)
    {
        if (!in_array($defaultState, [Barrier::ACTION_OPEN, Barrier::ACTION_CLOSE])) {
            throw new Exception('Invalid parameter: $defaultState. Allowed values: "open", "close".');
        }

        if (!in_array($outputState, [Barrier::ACTION_OPEN, Barrier::ACTION_CLOSE])) {
            throw new Exception('Invalid parameter: $outputState. Allowed values: "open", "close".');
        }

        if ($pulseDuration && !is_int($pulseDuration)) {
            throw new Exception('Invalid parameter: $pulseDuration. The value should be an integer representing the duration in milliseconds.');
        }

        if ($name && !is_string($name)) {
            throw new Exception('Invalid parameter: $name. The name must be a string.');
        }

        if ($ioUseType && !in_array($ioUseType, [Barrier::USE_TYPE_CUSTOM, Barrier::USE_TYPE_DISABLE, Barrier::USE_TYPE_ELECTRIC_LOCK])) {
            throw new Exception('Invalid parameter: $ioUseType. Allowed values: "disable", "electricLock", "custom".');
        }

        if ($normalStatus && !in_array($normalStatus, [Barrier::ACTION_OPEN, Barrier::ACTION_CLOSE])) {
            throw new Exception('Invalid parameter: $normalStatus. Allowed values: "open", "close".');
        }

        $defaultState = strtr($defaultState, [
            Barrier::ACTION_OPEN  => 'high',
            Barrier::ACTION_CLOSE => 'low',
        ]);

        $outputState = strtr($outputState, [
            Barrier::ACTION_OPEN  => 'high',
            Barrier::ACTION_CLOSE => 'low',
        ]);

        $response = $this->httpClient->put("/ISAPI/System/IO/outputs/{$this->portID}", <<<XML
            <IOOutputPort>
                <id>{$this->portID}</id>
                <PowerOnState>
                    <defaultState>{$defaultState}</defaultState>
                    <outputState>{$outputState}</outputState>
                    <pulseDuration>{$pulseDuration}</pulseDuration>
                </PowerOnState>
                <name>{$name}</name>
                <IOUseType>{$ioUseType}</IOUseType>
                <normalStatus>{$normalStatus}</normalStatus>
            </IOOutputPort>
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
     * Retrieves the status of a specified output.
     *
     * This method sends a GET request to the Hikvision device's API to fetch the status
     * of a specific output (identified by `portID`). It processes the XML response
     * and returns either a success or error response based on the result.
     *
     * @return array
     */
    public function getStatus()
    {
        $response = $this->httpClient->get("/ISAPI/System/IO/outputs/{$this->portID}/status");

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
     * Triggers a specified action on the barrier device.
     *
     * This method allows you to perform an action on the barrier device, such as opening,
     * closing signal. The action is validated, and an HTTP request
     * is sent to the device's API to execute the desired action.
     *
     * @param string $action The action to be triggered. Valid values are `Barrier::ACTION_OPEN`, `Barrier::ACTION_CLOSE`.
     *
     * @return array
     *
     * @throws Exception If an invalid action parameter is provided.
     */
    public function trigger($action)
    {
        if (!in_array($action, [Barrier::ACTION_OPEN, Barrier::ACTION_CLOSE])) {
            throw new Exception('Invalid parameter: $action. Allowed values: "open", "close".');
        }

        $action = strtr($action, [
            Barrier::ACTION_OPEN  => 'high',
            Barrier::ACTION_CLOSE => 'low',
        ]);

        $response = $this->httpClient->put("/ISAPI/System/IO/outputs/{$this->portID}/trigger", <<<XML
            <IOPortData>
                <outputState>{$action}</outputState>
            </IOPortData>
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
     * Open the barrier.
     *
     * This method triggers the action to open the barrier. It is a simple wrapper around the
     * `trigger` method with the `Barrier::ACTION_OPEN` constant passed as the action parameter.
     *
     * @return array The response from the trigger method, indicating success or failure.
     */
    public function open()
    {
        return $this->trigger(Barrier::ACTION_OPEN);
    }

    /**
     * Close the barrier.
     *
     * This method triggers the action to close the barrier. It is a simple wrapper around the
     * `trigger` method with the `Barrier::ACTION_CLOSE` constant passed as the action parameter.
     *
     * @return array The response from the trigger method, indicating success or failure.
     */
    public function close()
    {
        return $this->trigger(Barrier::ACTION_CLOSE);
    }
}
