<?php

/**
 * @fileoverview WATI WhatsApp API Service
 * @description Service for interacting with WATI WhatsApp Business API
 * @docs https://docs.wati.io/reference/
 */

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WatiService
{
    /**
     * HTTP Client
     */
    protected $client;

    /**
     * WATI API Endpoint
     */
    protected $apiEndpoint;

    /**
     * WATI Access Token
     */
    protected $accessToken;

    /**
     * API Version
     */
    protected $apiVersion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiEndpoint = config('whatsapp.wati.api_endpoint');
        $this->accessToken = config('whatsapp.wati.access_token');
        $this->apiVersion = config('whatsapp.wati.api_version', 'v1');

        $this->client = new Client([
            'base_uri' => $this->apiEndpoint,
            'timeout' => 30,
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get QR Code for WhatsApp connection
     *
     * @return array
     */
    public function getQRCode()
    {
        try {
            // Check cache first (QR codes expire quickly)
            $cacheKey = 'wati_qr_code_' . md5($this->accessToken);

            return Cache::remember($cacheKey, 30, function () {
                $response = $this->client->get("/api/{$this->apiVersion}/getQRCode");

                $data = json_decode($response->getBody()->getContents(), true);

                if (isset($data['qrCode'])) {
                    return [
                        'success' => true,
                        'qr_code' => $data['qrCode'],
                        'status' => $data['status'] ?? 'pending',
                        'message' => 'QR code retrieved successfully',
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Failed to retrieve QR code',
                    'error' => $data['message'] ?? 'Unknown error',
                ];
            });
        } catch (GuzzleException $e) {
            Log::error('WATI getQRCode error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to WATI API',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check QR Code scan status
     *
     * @return array
     */
    public function checkQRStatus()
    {
        try {
            $response = $this->client->get("/api/{$this->apiVersion}/checkQRStatus");

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['status'])) {
                // Clear QR cache if connected
                if ($data['status'] === 'connected') {
                    Cache::forget('wati_qr_code_' . md5($this->accessToken));
                }

                return [
                    'success' => true,
                    'status' => $data['status'], // pending, connected, disconnected
                    'phone' => $data['phone'] ?? null,
                    'message' => $this->getStatusMessage($data['status']),
                ];
            }

            return [
                'success' => false,
                'status' => 'unknown',
                'message' => 'Unable to check connection status',
            ];
        } catch (GuzzleException $e) {
            Log::error('WATI checkQRStatus error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to check connection status',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send WhatsApp message
     *
     * @param string $phone Phone number with country code (e.g., +1234567890)
     * @param string $message Message text
     * @return array
     */
    public function sendMessage($phone, $message)
    {
        try {
            $response = $this->client->post("/api/{$this->apiVersion}/sendSessionMessage/{$phone}", [
                'json' => [
                    'messageText' => $message,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return [
                'success' => true,
                'message_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'sent',
                'message' => 'Message sent successfully',
            ];
        } catch (GuzzleException $e) {
            Log::error('WATI sendMessage error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send WhatsApp message with media
     *
     * @param string $phone Phone number
     * @param string $mediaUrl URL to media file
     * @param string $caption Optional caption
     * @param string $type media type (image, video, document, audio)
     * @return array
     */
    public function sendMediaMessage($phone, $mediaUrl, $caption = '', $type = 'image')
    {
        try {
            $payload = [
                'mediaUrl' => $mediaUrl,
                'mediaCaption' => $caption,
            ];

            $endpoint = "/api/{$this->apiVersion}/sendSessionFile/{$phone}";

            $response = $this->client->post($endpoint, [
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return [
                'success' => true,
                'message_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'sent',
                'message' => 'Media message sent successfully',
            ];
        } catch (GuzzleException $e) {
            Log::error('WATI sendMediaMessage error', [
                'phone' => $phone,
                'mediaUrl' => $mediaUrl,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send media message',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get message delivery status
     *
     * @param string $messageId
     * @return array
     */
    public function getMessageStatus($messageId)
    {
        try {
            $response = $this->client->get("/api/{$this->apiVersion}/getMessageStatus/{$messageId}");

            $data = json_decode($response->getBody()->getContents(), true);

            return [
                'success' => true,
                'status' => $data['status'] ?? 'unknown',
                'delivered_at' => $data['deliveredAt'] ?? null,
                'read_at' => $data['readAt'] ?? null,
            ];
        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get connection status message
     *
     * @param string $status
     * @return string
     */
    protected function getStatusMessage($status)
    {
        switch ($status) {
            case 'connected':
                return 'WhatsApp is connected and ready';
            case 'pending':
                return 'Waiting for QR code scan';
            case 'disconnected':
                return 'WhatsApp is disconnected';
            default:
                return 'Unknown connection status';
        }
    }

    /**
     * Refresh QR code (clear cache)
     *
     * @return void
     */
    public function refreshQRCode()
    {
        Cache::forget('wati_qr_code_' . md5($this->accessToken));
    }

    /**
     * Check if WATI is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->apiEndpoint) && !empty($this->accessToken);
    }
}
