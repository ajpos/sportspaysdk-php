<?php

namespace Sportspay;

class Sportspay {
    private static $apiKey;
    private static $useSportspay = false;
    private static $baseUrl = 'https://testgate.interpaypos.com/api'; // Default base URL

    // Getter for API Key
    public static function getApiKey() {
        return self::$apiKey;
    }

    // Setter for API Key
    public static function setApiKey($apiKey) {
        self::$apiKey = $apiKey;
    }

    // Getter for useSportspay flag
    public static function isUsingSportspay() {
        return self::$useSportspay;
    }

    // Setter for useSportspay fla
    public static function useSportspay() {
        self::$useSportspay = true;
    }

    // Getter for Base URL
    public static function getBaseUrl() {
        return self::$baseUrl;
    }

    // Setter for Base URL (in case the base URL needs to be overridden)
    public static function setBaseUrl($baseUrl) {
        self::$baseUrl = $baseUrl;
    }

    public static function request($method, $params = [])
    {
        $ch = curl_init();

        // Set up cURL options
        curl_setopt($ch, CURLOPT_URL, self::$baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $postData = urldecode(http_build_query($params)).'JSON';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Check for HTTP errors
        if ($httpCode >= 400) {
            throw new \Exception("API request failed with HTTP code {$httpCode}: " . $response);
        }

        curl_close($ch);

        // Attempt to decode the JSON response
        $decodedResponse = json_decode($response, true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON response: " . json_last_error_msg());
        }

        return $decodedResponse;
    }
}
