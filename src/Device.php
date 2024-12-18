<?php
namespace Hikvision;

class Device
{
    /**
     * Device ip address
     *
     * @var string
     */
    public $ip;
    /**
     * Device port number
     *
     * @var string
     */
    public $port;
    /**
     * Username
     *
     * @var string
     */
    public $username;
    /**
     * Password
     *
     * @var string
     */
    public $password;

    /**
     * Constructor
     *
     * @param string $ip
     * @param string $port
     * @param string $username
     * @param string $password
     */
    public function __construct($ip, $port, $username, $password)
    {
        $this->ip       = $ip;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Fetches device information from the Hikvision API.
     *
     * @return array
     */
    public function getInfo()
    {
        $client   = new HttpClient($this->ip, $this->port, $this->username, $this->password);
        $response = $client->get('/ISAPI/System/deviceInfo');
        $response = Helpers::xmlToArray($response);

        if (isset($response['deviceID'])) {
            return $response;
        }

        return [
            'code'     => HttpClient::CODE_ERROR,
            'message'  => HttpClient::getErrorMessage($response),
            'response' => $response,
        ];
    }
}
