<?php

namespace Timodw\Translation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Timodw\Translation\Console\Commands\Translation;

class TranslationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Translation::class
            ]);
        }
        $this->publishes([
            __DIR__ . '/resources/js/plugins' => resource_path('js/plugins')
        ]);

        if (config('app.env') === 'local') {
            $lastModified = File::lastModified(resource_path('js/plugins/translations/translations.json'));

            $files = File::allFiles(resource_path('lang'));
            foreach ($files as $file) {
                if ($file->getMTime() > $lastModified) {
                    \Artisan::call('VueTranslation:generate');
                }
            }
        }
    }

    public function register()
    {
        $this->app->singleton('VueTranslation', function () {
            return new LaravelVueTranslation(new LaravelTranslationFileHelper());
        });
    }

}
