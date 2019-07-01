<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class FileCaching implements CacheInterface
{

    /**
     * Store a mixed type value in cache for a certain amount of seconds.
     * Allowed values are primitives and arrays.
     *
     * @param string $key
     * @param mixed $value
     * @param int $duration Duration in seconds
     * @return mixed
     */
    public function set(string $key, $value, int $duration){
        return Cache::put($key, $value, Carbon::now()->addSeconds($duration));
    }

    /**
     * Retrieve stored item.
     * Returns the same type as it was stored in.
     * Returns null if entry has expired.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key){
        return Cache::get($key);
    }

    public function find(string $key){
        return Cache::has($key);
    }

}
