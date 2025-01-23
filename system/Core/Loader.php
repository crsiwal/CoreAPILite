<?php

namespace System\Core;

use App\Configs\Constants;

class Loader {

    public static function init() {
        $autoloadPath = Constants::CONFIGS_DIR_PATH . 'autoload.php';

        if (!file_exists($autoloadPath)) {
            throw new \Exception("Autoload file not found: " . $autoloadPath);
        }

        $autoload = include $autoloadPath;

        if (!is_array($autoload)) {
            throw new \Exception("Invalid autoload configuration format.");
        }

        $autoloadSections = ['configs', 'libraries', 'helpers', 'languages'];

        foreach ($autoloadSections as $section) {
            if (isset($autoload[$section]) && is_array($autoload[$section])) {
                $method = 'load' . ucfirst($section);
                if (method_exists(__CLASS__, $method)) {
                    foreach ($autoload[$section] as $item) {
                        self::$method($item);
                    }
                } else {
                    throw new \Exception("Method not found: " . $method);
                }
            }
        }
    }

    public static function loadConfigs($config) {
        if (file_exists($appPath = Constants::CONFIGS_DIR_PATH . $config . ".php")) {
            include_once $appPath;
        } elseif (file_exists($systemPath = Constants::SYSTEM_PATH . "Configs/" . $library . ".php")) {
            include_once $systemPath;
        }
    }

    public static function loadLibraries($library) {
        if (file_exists($appPath = Constants::LIBRARIES_DIR_PATH . $library . ".php")) {
            include_once $appPath;
        } elseif (file_exists($systemPath = Constants::SYSTEM_PATH . "Libraries/" . $library . ".php")) {
            include_once $systemPath;
        }
    }

    public static function loadHelpers($helper) {
        if (file_exists($appPath = Constants::HELPERS_DIR_PATH . $helper . ".php")) {
            include_once $appPath;
        } elseif (file_exists($systemPath = Constants::SYSTEM_PATH . "Helpers/" . $helper . ".php")) {
            include_once $systemPath;
        }
    }

    public static function loadLanguages($language) {
        if (file_exists($appPath = Constants::LANGUAGES_DIR_PATH . $language . ".php")) {
            include_once $appPath;
        }
    }
}
