<?php

namespace AmjadAH\LaravelTranslation\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TranslateScope
{
    /**
     * Get translated field for a specific language
     *
     * @param Builder $query
     * @param string $lang
     * @param string $key
     * @return void
     */
    public function scopeTranslate(Builder $query, string $lang, string $key): void
    {
        $query->where('lang_key', '=', $lang)
            ->where('field_key', '=', $key);
    }
}