<?php

namespace App\Configs;

class Constants {
    const APP_DIR_NAME = 'app';
    const LOGS_DIR_NAME = 'logs';
    const CONFIGS_DIR_NAME = 'Configs';
    const CONTROLLERS_DIR_NAME = 'Controllers';
    const HELPERS_DIR_NAME = 'Helpers';
    const LANGUAGES_DIR_NAME = 'Languages';
    const LIBRARIES_DIR_NAME = 'Libraries';
    const MODELS_DIR_NAME = 'Models';
    const PUBLIC_DIR_NAME = 'public';
    const DEFAULT_RECORDS_PER_PAGE = 25;
    const DEFAULT_PAGE             = 1;
    const APP_PATH = BASEPATH . self::APP_DIR_NAME .  DIRECTORY_SEPARATOR;
    const PUBLIC_DIR_PATH = BASEPATH . self::PUBLIC_DIR_NAME . DIRECTORY_SEPARATOR;
    const CONFIGS_DIR_PATH = self::APP_PATH . self::CONFIGS_DIR_NAME . DIRECTORY_SEPARATOR;
    const CONTROLLERS_DIR_PATH = self::APP_PATH . self::CONTROLLERS_DIR_NAME . DIRECTORY_SEPARATOR;
    const HELPERS_DIR_PATH = self::APP_PATH . self::HELPERS_DIR_NAME . DIRECTORY_SEPARATOR;
    const LANGUAGES_DIR_PATH = self::APP_PATH . self::LANGUAGES_DIR_NAME . DIRECTORY_SEPARATOR;
    const LIBRARIES_DIR_PATH = self::APP_PATH . self::LIBRARIES_DIR_NAME . DIRECTORY_SEPARATOR;
    const MODELS_DIR_PATH = self::APP_PATH . self::MODELS_DIR_NAME . DIRECTORY_SEPARATOR;

    // Logs Constants
    const LOGS_LEVEL = 1;
    const LOGS_DIR_PATH = BASEPATH . "write" . DIRECTORY_SEPARATOR . self::LOGS_DIR_NAME . DIRECTORY_SEPARATOR;
}
