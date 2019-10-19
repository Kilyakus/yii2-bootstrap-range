<?php
namespace kilyakus\widget\range;

class RangeAsset extends \yii\web\AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';

        $this->js[] = 'js/bootstrap-range.js';
        $this->css[] = 'css/bootstrap-range.css';

        parent::init();
    }
}