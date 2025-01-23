<?php

namespace App\Configs;

class Constants {
    const APP_DIR_NAME = 'app';
    const CONFIGS_DIR_NAME = 'Configs';
    const CONTROLLERS_DIR_NAME = 'Controllers';
    const HELPERS_DIR_NAME = 'Helpers';
    const LANGUAGES_DIR_NAME = 'Languages';
    const LIBRARIES_DIR_NAME = 'Libraries';
    const MODELS_DIR_NAME = 'Models';
    const PUBLIC_DIR_NAME = 'public';
    const APP_PATH = BASEPATH . self::APP_DIR_NAME .  '/';
    const PUBLIC_DIR_PATH = BASEPATH . self::PUBLIC_DIR_NAME . '/';
    const CONFIGS_DIR_PATH = self::APP_PATH . self::CONFIGS_DIR_NAME . '/';
    const CONTROLLERS_DIR_PATH = self::APP_PATH . self::CONTROLLERS_DIR_NAME . '/';
    const HELPERS_DIR_PATH = self::APP_PATH . self::HELPERS_DIR_NAME . '/';
    const LANGUAGES_DIR_PATH = self::APP_PATH . self::LANGUAGES_DIR_NAME . '/';
    const LIBRARIES_DIR_PATH = self::APP_PATH . self::LIBRARIES_DIR_NAME . '/';
    const MODELS_DIR_PATH = self::APP_PATH . self::MODELS_DIR_NAME . '/';

    // System constants
    const SYSTEM_DIR_NAME = 'system';
    const SYSTEM_PATH = BASEPATH . self::SYSTEM_DIR_NAME . '/';
}
