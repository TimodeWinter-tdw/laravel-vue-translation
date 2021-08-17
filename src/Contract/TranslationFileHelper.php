<?php


namespace Tohidplus\Translation\Contract;


interface TranslationFileHelper
{
    public function fetch();

    public function write(array $data, string $path = null);

    public function resourcePath();

    public function destinationPath();
}
