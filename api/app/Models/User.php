<?php

namespace App\Models;

use App\Services\WeatherApiService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function weatherForecast(): HasOne
    {
        return $this->hasOne(WeatherForecast::class, 'user_id', 'id');
    }

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function($item) {
            $weatherApiService = new WeatherApiService();

            $weatherData = $weatherApiService->getUserWeatherData($item->latitude, $item->longitude);
            if($weatherData) {
                WeatherForecast::query()->create([
                    'user_id' => $item->id,
                    'data' => json_encode($weatherData)
                ]);
            }
        });

        static::updated(function($item) {
            $weatherApiService = new WeatherApiService();

            $weatherData = $weatherApiService->getUserWeatherData($item->latitude, $item->longitude);
            if($weatherData) {
                WeatherForecast::query()->updateOrCreate([
                    'user_id' => $item->id
                ], ['data' => json_encode($weatherData)]);
            }
        });

    }
}
