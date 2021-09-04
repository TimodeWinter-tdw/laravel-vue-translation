<?php

namespace Timodw\Translation;

use Timodw\Translation\Contract\TranslationFileHelper;

class LaravelVueTranslation

{
    /**
     * @var array $translations
     */
    private $translations = [];
    /**
     * @var TranslationFileHelper
     */
    private $translationFileHelper;

    /**
     * LaravelJSTranslation constructor.
     * @param TranslationFileHelper $translationFileHelper
     */
    public function __construct(TranslationFileHelper $translationFileHelper)
    {
        $this->translationFileHelper = $translationFileHelper;
    }

    public function compile()
    {
        $this->setTranslations();
        $this->translationFileHelper->write($this->translations);

    }

    private function addArrayLevels(array $keys, array $target, $data)
    {
        if ($keys) {
            $key = array_shift($keys);
            if (!count($keys)) {
                $target[$key] = $data;
            } else {
                $target[$key] = $this->addArrayLevels($keys, [], $data);
            }
        }
        return $target;
    }

    private function setTranslations()
    {
        $this->translations = [];
        foreach ($this->translationFileHelper->fetch() as $file) {
            $path = $file->getRelativePathName();

            $delimiter = strpos($path, '/') !== false ? '/' : '\\';
            
            $array = array_map(function ($key) use ($file) {
                return str_replace('.' . $file->getExtension(), '', $key);
            }, explode($delimiter, $path));

            $nestedArray = $this->addArrayLevels($array, [], 
                $file->getExtension() === 'json'  
                    ? json_decode(file_get_contents($file->getPathName()), true)
                    : require $file->getPathName()
            );
            
            $this->translations = array_merge_recursive($this->translations, $nestedArray);
        }
    }
}
