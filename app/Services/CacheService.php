<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function remember($key, $ttl, $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    public function forget($key)
    {
        Cache::forget($key);
    }

    public function lock($key, $seconds)
    {
        return Cache::lock($key, $seconds);
    }

    public function get($key)
    {
        return Cache::get($key);
    }

    public function put($key, $value, $ttl)
    {
        return Cache::put($key, $value, $ttl);
    }
}