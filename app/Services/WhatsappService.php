<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send message to a specific number.
     *
     * @param string $target Number in international format (e.g. 628123456789)
     * @param string $message The message content
     * @return bool
     */
    public static function send($target, $message)
    {
        $enabledSetting = \App\Models\Setting::where('key', 'wa_gateway_enabled')->value('value') ?? 'no';
        if ($enabledSetting !== 'yes') {
            Log::info("WhatsApp Disabled. Target: $target, Msg: $message");
            return false;
        }

        $token = \App\Models\Setting::where('key', 'wa_gateway_token')->value('value');
        $provider = \App\Models\Setting::where('key', 'wa_gateway_provider')->value('value') ?? 'fonnte';
        $sender = \App\Models\Setting::where('key', 'wa_gateway_sender')->value('value');

        if (empty($token)) {
            Log::warning("WhatsApp API Token is missing!");
            return false;
        }

        $endpoint = 'https://api.fonnte.com/send';
        $postData = [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ];

        if ($provider === 'wablas') {
            $endpoint = 'https://api.wablas.com/api/send-message';
            $postData = [
                'phone' => $target,
                'message' => $message,
            ];
            if (!empty($sender)) {
                $postData['sender'] = $sender;
            }
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($endpoint, $postData);

            if ($response->successful()) {
                Log::info("WA Sent Successfully using $provider to $target");
                return true;
            } else {
                Log::error("WA Failed using $provider to $target. Status: " . $response->status() . " Body: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WA Exception using $provider: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper to format Indonesian phone numbers.
     * 0812... -> 62812...
     */
    public static function formatNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }
        return $number;
    }
}
