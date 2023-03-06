<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherApiService {

    private string $api_key;
    private string $api_url;
    private string $units;

    public function __construct()
    {
        $this->api_url = config('weatherApi.api_url');
        $this->api_key = config('weatherApi.api_key');
        $this->units = config('weatherApi.units');
    }

    /**
     * @param string $lat
     * @param string $lng
     * @return string|null
     */
    public function getUserWeatherData(string $lat, string $lng): array | null
    {
        $response = Http::get($this->api_url.'/data/2.5/weather', [
                'lat' => $lat,
                'lon' => $lng,
                'appid' => $this->api_key,
                'units' => $this->units
            ]);

        if($response->failed()) {
            return null;
        }
        return $response->json()['main'];
    }
}
