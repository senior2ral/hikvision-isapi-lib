<?php
namespace Hikvision;

use SimpleXMLElement;

class Helpers
{
    /**
     * Extracts the IP address from a given URL.
     *
     * This method attempts to retrieve the IP address from a URL by first parsing it to find the host.
     * If the host is present, it is returned directly. Otherwise, a regular expression is used to search
     * for an IP address pattern within the provided URL.
     *
     * @param string $url The URL from which the IP address is to be extracted.
     *
     * @return string|null Returns the IP address as a string if found, or null if no IP address is present.
     */
    public static function extractIpFromUrl($url)
    {
        // Parse the URL to extract its components
        $parsedUrl = parse_url($url);

        // Check if the 'host' part exists and return it
        if (isset($parsedUrl['host'])) {
            return $parsedUrl['host'];
        }

        // If no host is found, use REGEX to search for an IP address in the URL
        preg_match('/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/', $url, $matches);

        // Return the matched IP address or null if not found
        return $matches[0] ?? null;
    }

    /**
     * Checks if the provided string is a valid JSON.
     *
     * @param string $string Yoxlanacaq mətn
     * @return bool
     */
    public static function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Checks if the provided string is valid XML.
     *
     * @param string $string Yoxlanacaq mətn
     * @return bool
     */
    public static function isXml($string): bool
    {
        if (!is_string($string)) {
            return false;
        }

        libxml_use_internal_errors(true);
        $doc = @simplexml_load_string($string);
        if (!$doc) {
            libxml_clear_errors();
            return false;
        }
        return true;
    }

    /**
     * Converts XML response to an associative array.
     *
     * @param string $response XML cavabı
     * @return array
     * @throws Exception
     */
    public static function xmlToArray($response): array
    {
        if (!self::isXml($response)) {
            throw new Exception("Etibarsız XML cavabı.");
        }

        $xml  = simplexml_load_string($response);
        $json = json_encode($xml);

        if (!self::isJson($json)) {
            throw new Exception("Etibarsız XML cavabı.");
        }

        $array = json_decode($json, true);

        if (isset($array['@attributes'])) {
            unset($array['@attributes']);
        }

        return $array;
    }

    /**
     * addArrayToXml
     *
     * @param  SimpleXMLElement $xmlElement
     * @param  array $data
     * @return void
     */
    public static function addArrayToXml(SimpleXMLElement $xmlElement, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::addArrayToXml($xmlElement->addChild($key), $value);
            } else {
                $xmlElement->addChild($key, htmlspecialchars($value));
            }
        }
    }

    /**
     * Searches for a substring in an array and returns the first matching element.
     *
     * This method iterates over an array (`haystack`) and checks if a specified substring (`needle`)
     * is present in each element. If a match is found, it returns the first matching element.
     * If no match is found, it returns false.
     *
     * @param string $needle The substring to search for.
     * @param array $haystack The array of strings to search in.
     *
     * @return string|false The first element that contains the substring, or false if no match is found.
     */
    public static function arrayFind($needle, $haystack)
    {
        foreach ($haystack as $item) {
            // Check if the needle is found in the current item
            if (strpos($item, $needle) !== false) {
                return $item; // Return the first matching element
            }
        }

        return false; // No match found
    }
}
