<?php

namespace AmjadAH\LaravelTranslation\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface TranslatableInterface
{
    /**
     * Get the translation fields
     *
     * @return HasMany
     */
    public function translations(): HasMany;
}
