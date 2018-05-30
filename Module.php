<?php

namespace spk\scheduler;

use yii;

class Module extends \yii\base\Module
{
    public $defaultRoute = 'scheduler';

    public $defaultFolder;

    public $folderDepth = 0;

    public function init()
    {
        parent::init();

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'spk\scheduler\console';
        } else {
            $this->controllerNamespace = 'spk\scheduler\controllers';
            if (!isset($this->defaultFolder)) {
                $this->defaultFolder = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'models';
            }
        }
    }

    public function createController($route)
    {
        preg_match("/{$this->defaultRoute}/", $route, $match);
        if (isset($match[0])) {
            return parent::createController($route);
        }
        return parent::createController("{$this->defaultRoute}/{$route}");
    }
}