<?php
namespace kilyakus\range;

class RangeAsset extends \yii\web\AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
    }
    public $css = [
        'css/bootstrap-range.css',
    ];
    public $js = [
        'js/bootstrap-range.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}