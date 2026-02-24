<?php

namespace App\Controllers;

class TruthscreenProxy extends BaseController
{
    /**
     * Simple proxy to TruthScreen eaadhaardigilocker API.
     * Expects JSON: { "requestData": "<encrypted payload>" }
     * Forwards to TruthScreen and returns their JSON response.
     */
    public function eaadhaar()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed',
            ]);
        }

        $json = $this->request->getJSON(true) ?? [];
        $requestData = $json['requestData'] ?? null;

        if (!$requestData || !is_string($requestData)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Missing or invalid requestData.',
            ]);
        }

        $baseUrl  = 'https://www.truthscreen.com';
        $endpoint = '/api/v1.0/eaadhaardigilocker/';
        $username = getenv('TRUTHSCREEN_CLIENT_ID') ?: 'test@theodin.in';

        $payload = json_encode(['requestData' => $requestData]);

        $ch = curl_init($baseUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'username: ' . $username,
            ],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response  = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError) {
            return $this->response->setStatusCode(502)->setJSON([
                'success' => false,
                'message' => 'Failed to contact TruthScreen service.',
                'error'   => $curlError,
            ]);
        }

        // Try to pass through JSON exactly as TruthScreen returned it
        $decoded = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->response
                ->setStatusCode($httpCode >= 200 && $httpCode < 600 ? $httpCode : 200)
                ->setJSON($decoded);
        }

        // Fallback: return raw body
        return $this->response
            ->setStatusCode($httpCode >= 200 && $httpCode < 600 ? $httpCode : 200)
            ->setHeader('Content-Type', 'application/json')
            ->setBody($response);
    }
}

