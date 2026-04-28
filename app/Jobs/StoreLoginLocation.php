<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreLoginLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId, $latitude, $longitude, $ipAddress, $historyId;

    public function __construct($userId, $latitude, $longitude, $ipAddress, $historyId = null)
    {
        $this->userId    = $userId;
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
        $this->ipAddress = $ipAddress;
        $this->historyId = $historyId;
    }

    public function handle()
    {
        try {
            $user = User::find($this->userId);
            if (!$user) return;

            // Update user lat/long
            $user->update([
                'latitude'  => $this->latitude,
                'longitude' => $this->longitude,
            ]);

            // Reverse geocoding
            $address = null;
            try {
                $apiKey  = "pk.1657d640f433dbcd0b009e097699adc6";
                $url     = "https://us1.locationiq.com/v1/reverse.php?key={$apiKey}&lat={$this->latitude}&lon={$this->longitude}&format=json&addressdetails=1";
                $response = Http::timeout(4)->get($url);
                $json     = $response->json();

                if (!empty($json['address'])) {
                    $parts = [
                        $json['address']['house_number'] ?? null,
                        $json['address']['road']         ?? null,
                        $json['address']['neighbourhood'] ?? null,
                        $json['address']['suburb']       ?? null,
                        $json['address']['city']         ?? null,
                        $json['address']['state']        ?? null,
                        $json['address']['postcode']     ?? null,
                        $json['address']['country']      ?? null,
                    ];
                    $address = implode(', ', array_filter($parts));
                } else {
                    $address = $json['display_name'] ?? null;
                }

                if ($address) {
                    $user->update(['location_address' => $address]);
                }
            } catch (\Exception $e) {
                Log::error("Reverse Geocoding Error: " . $e->getMessage());
            }

            // Update the login history record created synchronously at login time
            if ($this->historyId) {
                LoginHistory::where('id', $this->historyId)->update([
                    'location_address' => $address,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("StoreLoginLocation Job Error: " . $e->getMessage());
        }
    }
}
