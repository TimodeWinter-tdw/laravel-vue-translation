<?php

namespace Timodw\Translation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Timodw\Translation\Console\Commands\Translation;
use Timodw\Translation\Facades\VueTranslation;

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
            $path = resource_path('js/plugins/translations/translations.json');
            if (!File::exists($path)) {
                return;
            }

            $lastModified = File::lastModified($path);

            $files = File::allFiles(resource_path('lang'));
            foreach ($files as $file) {
                if ($file->getMTime() > $lastModified) {
                    VueTranslation::compile();
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
