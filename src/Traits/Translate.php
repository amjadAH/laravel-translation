<?php

namespace AmjadAH\LaravelTranslation\Traits;

use AmjadAH\LaravelTranslation\Contracts\TranslatableInterface;
use Illuminate\Support\Str;

trait Translate
{
    public function translate(string $key, TranslatableInterface $translatable, array $values): void
    {
        foreach (config('lang.languages') as $lang) {
            if ($lang != config('lang.default')) {
                if (isset($values[$lang]) && !is_null($values[$lang])) {
                    $this->updateOrCreateField($lang, $translatable, $key, $values[$lang]);
                }
            }
        }
    }

    /**
     * Check if field exists and update or create it if needed
     *
     * @param string $lang
     * @param TranslatableInterface $model
     * @param string $field
     * @param string $value
     * @return void
     */
    private function updateOrCreateField(string $lang, TranslatableInterface $model, string $field, string $value): void
    {
        $translatableModel = $this->getTranslationModel($model);
        $translationForeignKey = $this->getTranslationForeignKey($model);

        call_user_func("{$translatableModel}::updateOrCreate", [
            'lang_key' => $lang,
            $translationForeignKey => $model->id,
            'field_key' => $field
        ], [
            'field_value' => $value
        ]);
    }

    /**
     * Returns the translation model's name
     * it could be set by setting a protected property in the model called 'translationModel'
     *
     * @param TranslatableInterface $model
     * @return string
     */
    private function getTranslationModel(TranslatableInterface $model): string
    {
        if (isset($this->translationModel)) {
            return $this->translationModel;
        }

        return get_class($model) . 'Translation';
    }

    /**
     * Returns the translation foreign key
     * it could be set by setting a protected property in the model called 'translationForeignKey'
     *
     * @param TranslatableInterface $model
     * @return string
     */
    private function getTranslationForeignKey(TranslatableInterface $model): string
    {
        if (isset($this->translationForeignKey)) {
            return $this->translationForeignKey;
        }

        return Str::singular($model->getTable()) . '_id';
    }
}