# Laravel translation

## Prerequisites
> php8.0^, laravel8.*^

## What it does
The package allows you to manage multi-language apps in database

## How to use

```bash
$ php artisan make:translatable Article
```

This command will generate 4 files, 2 migrations file & 2 models, the first model will be called `Article`, the second one will be called `ArticleTranslation`, I guess you know the tables names now,

let's look at the migrations now, the `articles` table migration is an empty migration file, where you will fill your columns in, the second one will be like this
```php
Schema::create('article_translations', function (Blueprint $table) {
    $table->id();
    $table->string('lang_key')->comment('the iso code of the language ex: `en`, `es`');
    $table->string('field_key');
    $table->longText('field_value');
    $table->foreignId('article_id')
        ->references('id')
        ->on('articles')
        ->onDelete('cascade');
    $table->timestamps();

    $table->index(['article_id', 'lang_key']);

});
```

the models are very simple, the first one `Article`, will implement `AmjadAH\LaravelTranslation\Contracts\TranslatableInterface`, and use `AmjadAH\LaravelTranslation\Traits\Translatable`,
the other one `ArticleTranslation` will implement `AmjadAH\LaravelTranslation\Contracts\TranslationsInterface`, and use `AmjadAH\LaravelTranslation\Traits\TranslateScope`.

now publish the config file to edit the languages you are using and set the default language
```bash
$ php artisan vendor:publish --provider="AmjadAH\LaravelTranslation\TranslationServiceProvider"
```

---

now all you have to do is, in your "controller, service..." use the trait `AmjadAH\LaravelTranslation\Traits\Translate`, for example:
```php
use AmjadAH\LaravelTranslation\Traits\Translate;
use App\Models\Article;

class Service {
    use Translate;

    public function store() {
        $article = new Article;
        $article->title = 'Article title in English'; // this will be the default language
        $article->save();
        
        $this->translate('title', $article, [ // there we will store the other languages
            'es' => 'Article title in Spanish',
            'ar' => 'Article title in Arabic'
        ]);
    }
}
```

---

now to retrieve the corresponding language, all you have to do is:
```php
$article = \App\Models\Article::first();

$article->trans('title'); // this will return the corresponding language to the locale language
$article->trans('title', 'es'); // this will return the chosen language
```