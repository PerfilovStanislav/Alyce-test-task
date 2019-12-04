<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Facades\Redis;
use ShortCode\Random;

class ShortenUrlService
{
    /**
     * генерация короткого урла и сохранение в редис и базу
     * @param string $url
     * @return string
     */
    public function generateShortUrl(string $url) : string
    {
        // Генерируем короткий урл
        $shortUrl = Random::get(6);

        // сохраняем в базу
        (new ShortUrl())->fill([
            'url' => $url,
            'short_url' => $shortUrl,
        ])->save();

        // сохраняем в редис
        Redis::setex("url:{$shortUrl}", env('REDIS_SHORT_URL_TTL'), $url);

        return $shortUrl;
    }

    /**
     * Получение полного урла по короткому
     * @param string $shortUrl
     * @return string
     * @throws \Exception
     */
    public function getFullUrl(string $shortUrl) : string
    {
        // Вытаскиваем url из редиса
        $url = Redis::get("url:{$shortUrl}");
        if ($url) {
            return $url;
        }

        // В редисе не нашли, пытаемся найти в базе
        /** @var ShortUrl $model */
        $model = ShortUrl::query()->where('short_url', $shortUrl)->first();
        if ($model) {
            return $model->url;
        }

        throw new \Exception('Не найден короткий урл:' . $shortUrl);
    }

}
