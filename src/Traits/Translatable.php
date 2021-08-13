<?php

namespace AmjadAH\LaravelTranslation\Traits;

use AmjadAH\LaravelTranslation\Contracts\TranslatableInterface;
use AmjadAH\LaravelTranslation\Contracts\TranslationsInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

trait Translatable
{
    /**
     * @param string $field
     * @param string|null $lang
     *
     * @return string
     * @throws Exception
     */
    public function trans(string $field, string $lang = null): string
    {
        if (!$this instanceof Model) {
            throw new Exception('Invalid class');
        }

        if (!$this instanceof TranslatableInterface) {
            throw new Exception('Invalid model');
        }

        if ($this->translations() instanceof TranslationsInterface) {
            throw new Exception('Invalid translation model');
        }

        if (is_null($lang)) {
            $lang = app()->getLocale();
        }

        if ($lang == config('lang.default')) {
            return $this->{$field} ?? '';
        }

        $translation = $this->translations()->translate($lang, $field)->first();

        if ($translation) {
            return $translation->field_value;
        }

        return $this->{$field} ?? '';
    }

    /**
     * Get the translation fields
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        $translationModel = $this->getTranslationModel();
        $foreignKey = $this->getTranslationForeignKey();

        return $this->hasMany($translationModel, $foreignKey);
    }

    /**
     * Returns the translation model's name
     * it could be set by setting a protected property in the model called 'translationModel'
     *
     * @return string
     */
    private function getTranslationModel(): string
    {
        if (isset($this->translationModel)) {
            return $this->translationModel;
        }

        return get_class($this) . 'Translation';
    }

    /**
     * Returns the translation foreign key
     * it could be set by setting a protected property in the model called 'translationForeignKey'
     *
     * @return string
     */
    private function getTranslationForeignKey(): string
    {
        if (isset($this->translationForeignKey)) {
            return $this->translationForeignKey;
        }

        return Str::singular($this->getTable()) . '_id';
    }
}
