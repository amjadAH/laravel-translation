<?php

namespace AmjadAH\LaravelTranslation\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

class GenerateTranslationFiles extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:translatable {name}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * The path of the stub file.
     *
     * @var string
     */
    private string $path;

    /**
     * Where the files should be copied to.
     *
     * @var string
     */
    protected string $destinyPath;

    /**
     * The name of the model to be generated.
     *
     * @var string
     */
    private string $model;

    /**
     * The name of the translation model to be generated.
     *
     * @var string
     */
    private string $translationModel;

    /**
     * The name of the migration class to be generated.
     *
     * @var string
     */
    private string $migrationClass;

    /**
     * The name of the migration file to be generated.
     *
     * @var string
     */
    private string $migrationFile;

    /**
     * The name of the translation migration class to be generated.
     *
     * @var string
     */
    private string $translationMigrationClass;

    /**
     * The name of the translation migration file to be generated.
     *
     * @var string
     */
    private string $translationMigrationFile;

    /**
     * The name of the main table to be generated.
     *
     * @var string
     */
    private string $table;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates 4 files, 2 models & 2 migrations, for the table you created & for the translations table';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): bool|null
    {
        $this->init();

        $this->handleModel();
        parent::handle();

        $this->handleTranslationModel();
        parent::handle();

        $this->handleMigration();
        parent::handle();

        $this->handleTranslationMigration();
        parent::handle();

        return true;
    }

    /**
     * Handle the main model.
     *
     * @return void
     */
    private function handleModel(): void
    {
        $this->path = __DIR__ . '/../stubs/model.stub';
        $this->name = $this->model;
        $this->type = 'Model';
        $this->destinyPath = $this->laravel['path'].'/Models/'.str_replace('\\', '/', $this->name).'.php';
    }

    /**
     * Handle the translation model.
     *
     * @return void
     */
    private function handleTranslationModel(): void
    {
        $this->path = __DIR__ . '/../stubs/translation_model.stub';
        $this->name = $this->translationModel;
        $this->type = 'Translation model';
        $this->destinyPath = $this->laravel['path'].'/Models/'.str_replace('\\', '/', $this->name).'.php';
    }

    /**
     * Handle the main migration.
     *
     * @return void
     */
    private function handleMigration(): void
    {
        $this->path = __DIR__ . '/../stubs/migration.stub';
        $this->name = $this->migrationClass;
        $this->type = 'Migration';
        $this->destinyPath = base_path() . '/database/migrations/'.str_replace('\\', '/',  $this->migrationFile);
    }

    /**
     * Handle the translation migration.
     *
     * @return void
     */
    private function handleTranslationMigration(): void
    {
        $this->path = __DIR__ . '/../stubs/translation_migration.stub';
        $this->name = $this->translationMigrationClass;
        $this->type = 'Translation migration';
        $this->destinyPath = base_path() . '/database/migrations/'.str_replace('\\', '/',  $this->translationMigrationFile);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace([
            '{{class}}',
            '{{table}}',
            '{{parent}}'
        ], [
            $class,
            $this->table,
            Str::snake($this->model)
        ], $stub);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return $this->name;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        return $this->destinyPath;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->path;
    }

    /**
     * init the vars that will be used in the class
     *
     * @return void
     */
    private function init(): void
    {
        $this->model = ucwords($this->argument('name'));
        $this->translationModel = "{$this->model}Translation";
        $this->migrationClass = 'Create' . Str::plural($this->model) . 'Table';
        $this->migrationFile = now()->format('Y_m_d_His') . '_' . Str::snake($this->migrationClass) . '.php';
        $this->translationMigrationClass = 'Create' . Str::plural($this->translationModel) . 'Table';
        $this->translationMigrationFile = now()->addSecond(1)->format('Y_m_d_His') . '_' . Str::snake($this->translationMigrationClass) . '.php';
        $this->table = Str::plural(Str::snake($this->model));
    }
}
