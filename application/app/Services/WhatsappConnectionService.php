<?php

namespace App\Services;

use App\Models\WhatsappConnection;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsappConnectionService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false
        ]);
    }

    /**
     * Create a new WhatsApp connection instance
     */
    public function createInstance($name, $phoneNumber, $type = 'baileys')
    {
        try {
            $instanceId = 'growsass_' . Str::random(12);

            $apiUrl = config('whatsapp.api_url', env('WHATSAPP_API_URL'));
            $apiKey = config('whatsapp.api_key', env('WHATSAPP_API_KEY'));

            // Create instance via API
            $response = $this->client->post($apiUrl . '/instance/create', [
                'headers' => [
                    'apikey' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'instanceName' => $instanceId,
                    'qrcode' => true,
                    'integration' => 'WHATSAPP-BAILEYS',
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Create connection in database
            $connection = WhatsappConnection::create([
                'connection_name' => $name,
                'connection_type' => $type,
                'status' => 'pending',
                'phone_number' => $phoneNumber,
                'connection_data' => [
                    'instance_id' => $instanceId,
                    'api_url' => $apiUrl,
                    'api_key' => $apiKey,
                ],
                'qr_code' => $responseData['qrcode']['base64'] ?? null,
                'is_active' => true
            ]);

            return [
                'success' => true,
                'connection' => $connection,
                'qrcode' => $responseData['qrcode']['code'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp connection creation failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get QR code for connection
     */
    public function getQRCode($connectionId)
    {
        try {
            $connection = WhatsappConnection::findOrFail($connectionId);

            $apiUrl = $connection->connection_data['api_url'] ?? config('whatsapp.api_url');
            $apiKey = $connection->connection_data['api_key'] ?? config('whatsapp.api_key');
            $instanceId = $connection->connection_data['instance_id'] ?? null;

            if (!$instanceId) {
                throw new \Exception('Instance ID not found');
            }

            $response = $this->client->get($apiUrl . '/instance/qrcode/' . $instanceId, [
                'headers' => [
                    'apikey' => $apiKey,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'qrcode' => $responseData['qrcode']['base64'] ?? $responseData['base64'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('QR code fetch failed', [
                'connection_id' => $connectionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check connection status
     */
    public function checkStatus($connectionId)
    {
        try {
            $connection = WhatsappConnection::findOrFail($connectionId);

            $apiUrl = $connection->connection_data['api_url'] ?? config('whatsapp.api_url');
            $apiKey = $connection->connection_data['api_key'] ?? config('whatsapp.api_key');
            $instanceId = $connection->connection_data['instance_id'] ?? null;

            $response = $this->client->get($apiUrl . '/instance/connectionState/' . $instanceId, [
                'headers' => [
                    'apikey' => $apiKey,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Update connection status
            $status = 'disconnected';
            if (isset($responseData['state']) && $responseData['state'] === 'open') {
                $status = 'connected';
                $connection->last_connected_at = now();
            }

            $connection->status = $status;
            $connection->save();

            return [
                'success' => true,
                'status' => $status,
                'details' => $responseData
            ];

        } catch (\Exception $e) {
            Log::error('Status check failed', [
                'connection_id' => $connectionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Disconnect and delete connection
     */
    public function deleteConnection($connectionId)
    {
        try {
            $connection = WhatsappConnection::findOrFail($connectionId);

            $apiUrl = $connection->connection_data['api_url'] ?? config('whatsapp.api_url');
            $apiKey = $connection->connection_data['api_key'] ?? config('whatsapp.api_key');
            $instanceId = $connection->connection_data['instance_id'] ?? null;

            // Logout from API
            if ($instanceId) {
                try {
                    $this->client->delete($apiUrl . '/instance/logout/' . $instanceId, [
                        'headers' => [
                            'apikey' => $apiKey,
                        ],
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Logout failed, continuing with deletion', [
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Delete from database
            $connection->delete();

            return [
                'success' => true,
                'message' => 'Connection deleted successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Connection deletion failed', [
                'connection_id' => $connectionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Restart connection
     */
    public function restartConnection($connectionId)
    {
        try {
            $connection = WhatsappConnection::findOrFail($connectionId);

            $apiUrl = $connection->connection_data['api_url'] ?? config('whatsapp.api_url');
            $apiKey = $connection->connection_data['api_key'] ?? config('whatsapp.api_key');
            $instanceId = $connection->connection_data['instance_id'] ?? null;

            $this->client->put($apiUrl . '/instance/restart/' . $instanceId, [
                'headers' => [
                    'apikey' => $apiKey,
                ],
            ]);

            $connection->status = 'pending';
            $connection->save();

            return [
                'success' => true,
                'message' => 'Connection restarted'
            ];

        } catch (\Exception $e) {
            Log::error('Connection restart failed', [
                'connection_id' => $connectionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
