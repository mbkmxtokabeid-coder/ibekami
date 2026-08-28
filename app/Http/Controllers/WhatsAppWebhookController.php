<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Verifikasi Webhook dari Meta (Method GET)
     * 
     * Saat mendaftarkan callback URL di Meta for Developers, Meta akan mengirimkan
     * HTTP GET request dengan parameter:
     * - hub.mode: 'subscribe'
     * - hub.verify_token: token yang diinput saat pendaftaran
     * - hub.challenge: string acak yang harus dikembalikan apa adanya
     */
    public function verify(Request $request)
    {
        $verifyToken = config('services.whatsapp.verify_token', 'token_rahasia_ibekami_123');

        $mode = $request->input('hub_mode') ?? $request->input('hub.mode');
        $token = $request->input('hub_verify_token') ?? $request->input('hub.verify_token');
        $challenge = $request->input('hub_challenge') ?? $request->input('hub.challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verifyToken) {
                Log::channel('daily')->info('WhatsApp Webhook verified successfully');
                // Meta mengharuskan return nilai hub_challenge sebagai plain text dengan status 200
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }

            Log::channel('daily')->warning('WhatsApp Webhook verification failed: Invalid verify token', [
                'received_token' => $token,
                'ip' => $request->ip()
            ]);

            return response()->json(['error' => 'Token tidak valid'], 403);
        }

        return response()->json(['error' => 'Bad Request'], 400);
    }

    /**
     * Menerima Pesan / Event Masuk dari WhatsApp (Method POST)
     * 
     * Meta mengirimkan update status (sent, delivered, read) atau pesan masuk (text, media, interactive)
     * dalam format JSON payload.
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        // Log seluruh data yang masuk untuk debugging dan audit
        Log::channel('daily')->info('WhatsApp Webhook payload received:', [
            'payload' => $data,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Cek struktur payload dari Meta (WhatsApp Cloud API)
        if (isset($data['object']) && $data['object'] === 'whatsapp_business_account') {
            if (!empty($data['entry'])) {
                foreach ($data['entry'] as $entry) {
                    if (!empty($entry['changes'])) {
                        foreach ($entry['changes'] as $change) {
                            $value = $change['value'] ?? [];

                            // 1. Jika ada pesan masuk dari user
                            if (!empty($value['messages'])) {
                                foreach ($value['messages'] as $message) {
                                    $from = $message['from'] ?? null; // Nomor WA pengirim
                                    $msgType = $message['type'] ?? null; // text, image, audio, interactive, dll
                                    $textBody = $message['text']['body'] ?? null;

                                    Log::channel('daily')->info('WhatsApp incoming message:', [
                                        'from' => $from,
                                        'type' => $msgType,
                                        'text' => $textBody,
                                        'timestamp' => $message['timestamp'] ?? null,
                                        'message_id' => $message['id'] ?? null,
                                    ]);

                                    // TODO: Proses pesan (misal: simpan ke database, forward ke notifikasi admin, atau auto-reply bot)
                                }
                            }

                            // 2. Jika ada status update pesan (sent, delivered, read, failed)
                            if (!empty($value['statuses'])) {
                                foreach ($value['statuses'] as $status) {
                                    Log::channel('daily')->info('WhatsApp message status update:', [
                                        'recipient_id' => $status['recipient_id'] ?? null,
                                        'status' => $status['status'] ?? null,
                                        'timestamp' => $status['timestamp'] ?? null,
                                        'message_id' => $status['id'] ?? null,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Meta mewajibkan respons HTTP 200 OK dalam waktu < 20 detik
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received'
        ], 200);
    }
}
