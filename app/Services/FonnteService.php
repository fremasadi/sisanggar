<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function send(array $targets, string $message): bool
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');

        $targets = collect($targets)
            ->filter()
            ->map(fn ($target) => preg_replace('/[^0-9]/', '', (string) $target))
            ->filter()
            ->unique()
            ->values();

        if (!$token || $targets->isEmpty()) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->withHeaders(['Authorization' => $token])
                ->timeout(20)
                ->post($endpoint, [
                    'target' => $targets->implode(','),
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if (!$response->successful() || $response->json('status') === false) {
                Log::warning('Fonnte notification failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Fonnte notification error', [
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
