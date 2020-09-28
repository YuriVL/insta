<?php

namespace classes;


class AdminWidget
{

    private $template = '<div class="file-loading">
    <input id="input-b7" name="input-b7[]" multiple="false" type="file" class="file" data-allowed-file-extensions=\'["json"]\'>
</div>';

    public static function widget()
    {
        return (new self)->run();
    }

    private function run($config = [])
    {
        return $this->template;
    }
}