<?php

namespace App\Services;

use App\Exceptions\IdempotencyConflictException;
use App\Exceptions\IdempotencyProcessingException;
use Illuminate\Support\Facades\Log;

class IdempotencyService
{
    protected $cache;

    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_SUCCESS = 'SUCCESS';

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    protected function buildKey($key)
    {
        return "idempotency:$key";
    }

    public function check($key, $payload)
    {
        $cacheKey = $this->buildKey($key);

        $data = $this->cache->get($cacheKey);

        if (!$data) {
            return null;
        }

        $data = json_decode($data, true);

        // check payload
        if ($data['hash'] !== $this->hash($payload)) {
            throw new IdempotencyConflictException();
        }

        return $data;
    }

    public function markProcessing($key, $payload)
    {
        $cacheKey = $this->buildKey($key);

        $lock = $this->cache->lock($cacheKey, 60);

        if (!$lock->get()) {
            return false;
        }

        $this->cache->put($cacheKey, json_encode([
            'status' => self::STATUS_PROCESSING,
            'hash' => $this->hash($payload)
        ]), 60);

        return true;
    }

    public function storeSuccess($key, $payload, $response)
    {
        $cacheKey = $this->buildKey($key);

        $this->cache->put($cacheKey, json_encode([
            'status' => self::STATUS_SUCCESS,
            'hash' => $this->hash($payload),
            'response' => $response->getData(),
            'status_code' => $response->getStatusCode()
        ]), 3600);
    }

    public function clear($key)
    {
        $this->cache->forget($this->buildKey($key));
    }

    protected function hash($payload)
    {
        ksort($payload);
        return md5(json_encode($payload));
    }

    public function shouldClear($e)
    {
        return !(
            $e instanceof IdempotencyConflictException ||
            $e instanceof IdempotencyProcessingException
        );
    }
}
