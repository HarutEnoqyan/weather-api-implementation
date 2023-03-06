<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UserWeatherController extends Controller
{
    /**
     * @var int
     */
    private int $defaultPerPage = 10;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->query('page') ?? 2;
        $perPage = $request->query('per_page') ?? $this->defaultPerPage;
        $cacheKey = 'users';

        if(Cache::has($cacheKey)) {
            $userCollection = Cache::get($cacheKey);
        }else {
            $users = User::query()->get();
            $userCollection = UserResource::collection($users);
            Cache::set($cacheKey, $userCollection, 60);
        }
//        dd($userCollection);
        $data = new LengthAwarePaginator(
            array_values($userCollection->forPage($page, $perPage)->toArray()), $userCollection->count(), $perPage, $page
        );
        return response()->json($data);
    }

    /**
     * @param $userId
     * @return JsonResponse
     */
    public function show($userId): JsonResponse
    {
        $cacheKey = 'users.' . $userId;

        if(Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
        }else {
            $user = User::query()->findOrFail($userId);
            $data = $user->weatherForecast ? json_decode($user->weatherForecast->data) : null;
            Cache::set($cacheKey, $data, 60);
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
