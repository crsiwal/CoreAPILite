<?php

namespace System\Core;

use App\Configs\Constants;

class Loader {
    public static function init() {

        $autoload = include Constants::CONFIGS_DIR_PATH . 'autoload.php';

        foreach ($autoload['libraries'] as $library) {
            self::loadLibrary($library);
        }

        foreach ($autoload['helpers'] as $helper) {
            self::loadHelper($helper);
        }

        foreach ($autoload['languages'] as $language) {
            self::loadLanguage($language);
        }
    }

    public static function loadLibrary($library) {
        $path = Constants::LIBRARIES_DIR_PATH . "/$library.php";



        if (file_exists($path)) {
            include_once $path;
        }
    }

    public static function loadHelper($helper) {
        $path = __DIR__ . "/../../Helpers/$helper.php";
        if (file_exists($path)) {
            include_once $path;
        }
    }

    public static function loadLanguage($language) {
        $path = __DIR__ . "/../../Languages/$language.php";
        if (file_exists($path)) {
            include_once $path;
        }
    }
}
