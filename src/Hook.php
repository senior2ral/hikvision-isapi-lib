<?php
namespace Hikvision;

use Exception;

class Hook extends DeviceManager
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
     * Retrieves the capabilities of the HTTP callback host configuration.
     *
     * This method sends a GET request to the Hikvision device API to fetch the capabilities of the HTTP
     * callback host configuration. It processes the XML response, checking for errors, and returns the
     * success or error response accordingly.
     *
     * @return array The result of the GET request (either success or error response).
     */
    public function getCapabilities()
    {
        $response = $this->httpClient->get('/ISAPI/Event/notification/httpHosts/capabilities');
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
     * Retrieves the information of a specific callback host.
     *
     * This method sends a GET request to the Hikvision device API to fetch the configuration information
     * of a specific callback host identified by its $hookID. The XML response is processed and any errors
     * are checked. It returns a success or error response accordingly.
     *
     * @param string|null $hookID The ID of the callback host whose information is to be retrieved.
     * @return array The result of the GET request (either success or error response).
     */
    public function getInfo($hookID = null)
    {
        $response = $this->httpClient->get("/ISAPI/Event/notification/httpHosts/{$hookID}");
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
     * Saves or updates the configuration of a callback host.
     *
     * This method sends a PUT request to the Hikvision device API to either save or update the configuration
     * of a callback host. It accepts a callback ID and a configuration array with various parameters, including
     * protocol type, authentication method, user credentials, and other settings specific to the callback host.
     * The method then sends the configuration to the device and processes the response.
     *
     * @param string|null $hookID The ID of the callback host to save or update.
     * @param array $config The configuration parameters for the callback host, including settings like protocol,
     *                      authentication method, URL, and other parameters.
     * @return array The result of the PUT request (either success or error response).
     * @throws Exception If there is an error in the response from the device.
     */
    public function save($hookID = null, $config = [])
    {
        $config = array_merge([
            'protocolType'             => 'HTTP',
            'parameterFormatType'      => 'XML',
            'addressingFormatType'     => 'ipaddress',
            'httpBroken'               => true,
            'httpAuthenticationMethod' => 'none',
            'userName'                 => '',
            'password'                 => '',
            'uploadImagesDataType'     => 'binary',
            'ANPR'                     => [
                'detectionUpLoadPicturesType' => 'all',
            ],
        ], $config);

        $response = $this->httpClient->put("/ISAPI/Event/notification/httpHosts/{$hookID}", <<<XML
            <HttpHostNotification>
                <id>{$config['id']}</id>
                <url>{$config['url']}</url>
                <ipAddress>{$config['ipAddress']}</ipAddress>
                <portNo>{$config['portNo']}</portNo>
                <protocolType>{$config['protocolType']}</protocolType>
                <parameterFormatType>{$config['parameterFormatType']}</parameterFormatType>
                <addressingFormatType>{$config['addressingFormatType']}</addressingFormatType>
                <httpBroken>{$config['httpBroken']}</httpBroken>
                <httpAuthenticationMethod>{$config['httpAuthenticationMethod']}</httpAuthenticationMethod>
                <userName>{$config['userName']}</userName>
                <password>{$config['password']}</password>
                <uploadImagesDataType>{$config['uploadImagesDataType']}</uploadImagesDataType>
                <ANPR>
                    <detectionUpLoadPicturesType>{$config['ANPR']['detectionUpLoadPicturesType']}</detectionUpLoadPicturesType>
                </ANPR>
            </HttpHostNotification>
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
     * Deletes a callback host from the device's event notification configuration.
     *
     * This method sends a DELETE request to the Hikvision device API to remove a specified
     * callback host identified by its $hookID. The response is processed and errors, if
     * any, are handled. It returns a structured response indicating the success or failure
     * of the operation.
     *
     * @param string $hookID The ID of the callback host to be deleted.
     * @return array The result of the DELETE request (either success or error response).
     * @throws Exception If $hookID is not provided.
     */
    public function delete($hookID)
    {
        if (!$hookID) {
            throw new Exception('Parameter $hookID is required');
        }

        $response = $this->httpClient->delete("/ISAPI/Event/notification/httpHosts/{$hookID}");
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
     * Tests the configuration of a callback host.
     *
     * This method sends a POST request to the Hikvision device API to test the configuration
     * of a specific callback host, identified by its $hookID. It merges the provided configuration
     * with default values, sends the request, and processes the response. The result of the test is returned,
     * including any success or error information.
     *
     * @param string|null $hookID The ID of the callback host to test.
     * @param array $config The configuration parameters for the callback host.
     * @return array The result of the POST request (success or error).
     */
    public function test($hookID = null, $config = [])
    {
        $config = array_merge([
            'protocolType'             => 'HTTP',
            'parameterFormatType'      => 'XML',
            'addressingFormatType'     => 'ipaddress',
            'httpBroken'               => true,
            'httpAuthenticationMethod' => 'none',
            'userName'                 => '',
            'password'                 => '',
            'uploadImagesDataType'     => 'binary',
            'eventType'                => 'AID',
        ], $config);

        $response = $this->httpClient->post("/ISAPI/Event/notification/httpHosts/{$hookID}/test", <<<XML
            <HttpHostNotification>
                <id>{$config['id']}</id>
                <url>{$config['url']}</url>
                <ipAddress>{$config['ipAddress']}</ipAddress>
                <portNo>{$config['portNo']}</portNo>
                <protocolType>{$config['protocolType']}</protocolType>
                <parameterFormatType>{$config['parameterFormatType']}</parameterFormatType>
                <addressingFormatType>{$config['addressingFormatType']}</addressingFormatType>
                <httpBroken>{$config['httpBroken']}</httpBroken>
                <httpAuthenticationMethod>{$config['httpAuthenticationMethod']}</httpAuthenticationMethod>
                <userName>{$config['userName']}</userName>
                <password>{$config['password']}</password>
                <eventType>{$config['eventType']}</eventType>
                <uploadImagesDataType>{$config['uploadImagesDataType']}</uploadImagesDataType>
            </HttpHostNotification>
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
