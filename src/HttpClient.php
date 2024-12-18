<?php
namespace Hikvision;

use Hikvision\Exception;

class HttpClient
{
    const CODE_SUCCESS = 200;
    const CODE_ERROR   = 400;

    /**
     * @var string Device IP address
     */
    private $ip;

    /**
     * @var string Device port number
     */
    private $port;

    /**
     * @var string Username for authentication
     */
    private $username;

    /**
     * @var string Password for authentication
     */
    private $password;

    /**
     * @var array HTTP headers
     */
    public $headers = [
        'Content-Type' => 'application/xml',
    ];

    /**
     * @var mixed Data payload for the request
     */
    public $data = null;

    /**
     * @var int Request timeout duration in seconds
     */
    public $timeOut = 60;

    /**
     * @var string User agent string
     */
    public $userAgent = 'Hikvision/API';

    /**
     * Constructor to initialize HTTP client with device details.
     *
     * @param string $ip Device IP address
     * @param string $port Device port number
     * @param string $username Username for authentication
     * @param string $password Password for authentication
     */
    public function __construct($ip, $port, $username, $password)
    {
        $this->ip       = $ip;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Sends an HTTP request to the Hikvision API.
     *
     * @param string $endpoint API endpoint
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @return array|string Response data
     * @throws Exception If an error occurs during the request
     */
    private function request($endpoint, $method = 'GET')
    {
        $url = 'http://' . $this->ip . ':' . $this->port . $endpoint;

        // Generate authorization token
        $token = $this->generateToken($method, $endpoint);
        if (!$token) {
            throw new Exception("Cannot generate authorization token");
        }
        $this->addHeader('Authorization', $token);

        // Prepare cURL options
        $ch          = curl_init();
        $curlOptions = [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeOut,
            CURLOPT_USERAGENT      => $this->userAgent,
            CURLOPT_HTTPHEADER     => array_map(function ($key, $value) {
                return "{$key}: {$value}";
            }, array_keys($this->headers), $this->headers),
        ];

        if (isset($this->data)) {
            $curlOptions[CURLOPT_POSTFIELDS] = $this->data;
        }

        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            throw new Exception($error);
        }

        return $response;
    }

    /**
     * Sends a GET request.
     *
     * @param string $endpoint API endpoint
     * @param mixed $data Optional data for the request
     * @return array|string Response data
     */
    public function get($endpoint, $data = null)
    {
        $this->setData($data);
        return $this->request($endpoint, 'GET');
    }

    /**
     * Sends a POST request.
     *
     * @param string $endpoint API endpoint
     * @param mixed $data Optional data for the request
     * @return array|string Response data
     */
    public function post($endpoint, $data = null)
    {
        $this->setData($data);
        return $this->request($endpoint, 'POST');
    }

    /**
     * Sends a PUT request.
     *
     * @param string $endpoint API endpoint
     * @param mixed $data Optional data for the request
     * @return array|string Response data
     */
    public function put($endpoint, $data = null)
    {
        $this->setData($data);
        return $this->request($endpoint, 'PUT');
    }

    /**
     * Sends a DELETE request.
     *
     * @param string $endpoint API endpoint
     * @param mixed $data Optional data for the request
     * @return array|string Response data
     */
    public function delete($endpoint, $data = null)
    {
        $this->setData($data);
        return $this->request($endpoint, 'DELETE');
    }

    /**
     * Generates a Digest Authentication token.
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @return string Authorization header value
     * @throws Exception If authentication fails
     */
    private function generateToken($method, $endpoint)
    {
        $url = 'http://' . $this->ip . ':' . $this->port . $endpoint;

        // Initial request to get the challenge
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);

        $response = curl_exec($ch);
        $headers  = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);

        // Extract Digest Authentication challenge
        preg_match('/WWW-Authenticate: Digest (.+)/i', $headers, $matches);
        if (!isset($matches[1])) {
            throw new Exception("Digest authentication header not found.");
        }

        $challengeData = [];
        preg_match_all('/(\w+)=["]?([^",]+)["]?/', $matches[1], $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $challengeData[$match[1]] = $match[2];
        }

        // Generate Digest Authentication response
        $realm  = $challengeData['realm'];
        $nonce  = $challengeData['nonce'];
        $uri    = parse_url($url, PHP_URL_PATH);
        $qop    = $challengeData['qop'] ?? null;
        $nc     = '00000001';
        $cnonce = md5(uniqid(mt_rand(), true));

        $HA1      = md5("{$this->username}:{$realm}:{$this->password}");
        $HA2      = md5("{$method}:{$uri}");
        $response = md5("{$HA1}:{$nonce}:{$nc}:{$cnonce}:{$qop}:{$HA2}");

        return "Digest username=\"{$this->username}\", realm=\"{$realm}\", nonce=\"{$nonce}\", uri=\"{$uri}\", response=\"{$response}\", qop={$qop}, nc={$nc}, cnonce=\"{$cnonce}\"";
    }

    /**
     * Adds a header to the request.
     *
     * @param string $key Header name
     * @param string $value Header value
     * @return self
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Sets the request payload.
     *
     * @param mixed $data Data payload
     * @return self
     */
    public function setData($data = null)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sets the request payload as JSON.
     *
     * @param mixed $data Data to be JSON-encoded
     * @return self
     */
    public function setJsonData($data = null)
    {
        $this->setData(json_encode($data));
        return $this;
    }

    /**
     * Extracts an error message from the response.
     *
     * This method checks for error-related keys in the response array and returns a meaningful error message.
     * If no specific error message is found, a default generic message is returned.
     *
     * @param array $response The response array to parse for error details.
     *                        Expected structure:
     *                        - 'statusCode': The main status code of the response.
     *                        - 'subStatusCode': The detailed sub-status code.
     *                        - 'statusString': A string describing the error (optional).
     *                        - 'description': A detailed error description (optional).
     *                        - 'body': Additional body content (optional).
     *
     * @return string|null Returns the error message if available, or null if no error is found.
     */
    public static function getErrorMessage($response = [])
    {
        $errorMessage = null;

        // Check if 'statusCode' is present in the response
        if (isset($response['statusCode'])) {
            // If 'subStatusCode' is not 'ok', determine the error message
            if ($response['subStatusCode'] != 'ok') {
                if (is_string($response['statusString'])) {
                    $errorMessage = $response['statusString'];
                } elseif (is_string($response['description'])) {
                    $errorMessage = $response['description'];
                } else {
                    $errorMessage = "Unknown error occurred for statusCode: {$response['statusCode']}";
                }
            }
        }
        // Check if error message is located in the 'body' key
        elseif (isset($response['body']['p'])) {
            $errorMessage = (string) $response['body']['p'];
        }

        // Return the extracted error message or null
        return $errorMessage;
    }

    /**
     * Checks if the response indicates a successful operation.
     *
     * This method verifies whether the provided response array represents a successful operation.
     * A response is considered successful if:
     * - The 'statusCode' key is present and equals '1'.
     * - The 'subStatusCode' key (if present) equals 'ok' (case-insensitive).
     * If the 'statusCode' key is not set, it defaults to true, assuming no errors exist.
     *
     * @param array $response The response array to validate.
     *                        Expected structure:
     *                        - 'statusCode': The main status code of the response (optional).
     *                        - 'subStatusCode': The detailed sub-status code (optional).
     *
     * @return bool Returns true if the response indicates success, false otherwise.
     */
    public static function isOk($response = [])
    {
        // Check if the response is valid and not empty
        if ($response) {
            // If 'statusCode' is not set, assume success
            if (!isset($response['statusCode'])) {
                return true;
            }
            // Check if 'statusCode' is '1' and 'subStatusCode' is 'ok'
            if ($response['statusCode'] == '1' && strtolower($response['subStatusCode']) == 'ok') {
                return true;
            }
        }

        // If any condition fails, return false
        return false;
    }
}
