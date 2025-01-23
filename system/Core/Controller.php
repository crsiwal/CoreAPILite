<?php

namespace System\Core;

use App\Configs\Constants;

class Controller {
    protected function loadModel($model) {
        $className = Constants::APP_DIR_NAME . "\\" . Constants::MODELS_DIR_NAME . "\\$model";
        if (class_exists($className)) {
            return new $className();
        }
        throw new \Exception("Model $model not found");
    }
}
