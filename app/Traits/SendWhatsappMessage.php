<?php

namespace App\Traits;

use App\Models\WhatsappApiConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

trait SendWhatsappMessage
{
    protected function sendWhatsappMessage($textMessage): void
    {
        $whatsappApiConfig = WhatsappApiConfig::first();

        if (!$whatsappApiConfig) {
            Log::error('WhatsApp API configuration not found.');
            return;
        }

        // TODO WANESIA
        $response = (new Client())->sendAsync(new Request('POST', $whatsappApiConfig->endpoint), [
            'form_params' => [
                'token' => $whatsappApiConfig->api_key,
                'number' => $textMessage['phone'],
                'message' => $textMessage['message'],
            ]
        ])->wait();

        Log::info('WANESIA Response: ' . $response->getBody());
    }
}
