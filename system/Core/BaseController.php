<?php

namespace System\Core;

class BaseController {
    protected function loadModel($model) {
        $className = "App\\Models\\$model";
        if (class_exists($className)) {
            return new $className();
        }
        throw new \Exception("Model $model not found");
    }
}
