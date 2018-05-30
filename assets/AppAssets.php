<?php

namespace spk\scheduler\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AppAssets extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/egorspk/yii2-scheduler/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'style.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'scripts.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];

    /**
     * @var array
     */
    public $jsOptions = [
        'position' => View::POS_END,
    ];

}
