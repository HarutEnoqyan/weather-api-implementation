<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WeatherForecast;
use App\Services\WeatherApiService;
use Illuminate\Console\Command;

class GetWeatherData extends Command
{
    private WeatherApiService $weatherApiService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get weather for each user by coordinates';

    /**
     * @param WeatherApiService $weatherApiService
     */
    public function __construct(WeatherApiService $weatherApiService)
    {
        $this->weatherApiService = $weatherApiService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::query()->get();
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();
        foreach ($users as $user) {
            $weatherData = $this->weatherApiService->getUserWeatherData($user->latitude, $user->longitude);
            if($weatherData) {
                WeatherForecast::query()->updateOrCreate([
                   'user_id' => $user->id
                ], ['data' => json_encode($weatherData)]);
            }else {
                $this->warn('No weather data found for user '. $user->email);
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
