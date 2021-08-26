<?php

namespace Timodw\Translation;

use Illuminate\Support\Facades\File;
use Timodw\Translation\Contract\TranslationFileHelper;

class LaravelTranslationFileHelper implements TranslationFileHelper
{

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function fetch()
    {
        return File::allFiles($this->resourcePath());
    }

    /**
     * @param array $data
     */
    public function write(array $data, string $path = null)
    {
        if ($path === null) {
            $path = $this->destinationPath();
        }

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        File::put($path . '/translations.json', json_encode($data));
    }

    /**
     * @return string
     */
    public function resourcePath()
    {
        return resource_path('lang');
    }

    /**
     * @return string
     */
    public function destinationPath()
    {
        return resource_path('js/plugins/translations');
    }
}
