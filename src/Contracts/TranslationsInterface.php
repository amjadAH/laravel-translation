<?php

namespace AmjadAH\LaravelTranslation\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface TranslationsInterface
{
    /**
     * Get translated field for a specific language
     *
     * @param Builder $query
     * @param string $lang
     * @param string $key
     * @return void
     */
    public function scopeTranslate(Builder $query, string $lang, string $key): void;
}
