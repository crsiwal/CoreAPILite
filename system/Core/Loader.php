<?php

namespace System\Core;

use App\Configs\Constants as App_Constants;
use System\Configs\Constants as System_Constants;

class Loader {

    public static function init() {
        $autoloadPath = App_Constants::CONFIGS_DIR_PATH . 'autoload.php';

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
        if (file_exists($appPath = App_Constants::CONFIGS_DIR_PATH . $config . ".php")) {
            include_once $appPath;
        } elseif (file_exists($systemPath = System_Constants::SYSTEM_CONFIGS_DIR_PATH . $library . ".php")) {
            include_once $systemPath;
        }
    }

    public static function loadLibraries($library) {
        if (file_exists($appPath = App_Constants::LIBRARIES_DIR_PATH . $library . ".php")) {
            include_once $appPath;
        } elseif (file_exists($systemPath = System_Constants::SYSTEM_LIBRARIES_DIR_PATH . $library . ".php")) {
            include_once $systemPath;
        }
    }

    public static function loadHelpers($helper) {
        if (file_exists($appPath = App_Constants::HELPERS_DIR_PATH . $helper . ".php")) {
            include_once $appPath;
        } elseif (file_exists($systemPath = System_Constants::SYSTEM_HELPERS_DIR_PATH . $helper . ".php")) {
            include_once $systemPath;
        }
    }

    public static function loadLanguages($language) {
        if (file_exists($appPath = App_Constants::LANGUAGES_DIR_PATH . $language . ".php")) {
            include_once $appPath;
        }
    }
}
