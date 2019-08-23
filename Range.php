<?php
namespace kilyakus\range;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Inflector;

class Range extends Widget
{
    public $model;

    public $attribute;

    public $name;

    public $size = 'sm';

    public $step = 1;

    public $min = 'min';

    public $max = 'max';

    public $range = true;

    public $tooltip = 'show'; // 'show', 'hide', or 'always'

    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            Yii::$app->session->setFlash('danger', 'Widget' . (new \ReflectionClass(get_class($this)))->getShortName() . ': ' . Yii::t('easyii', "Required `model` param isn\'t set."));
        }

        if (empty($this->attribute)) {
            Yii::$app->session->setFlash('danger', 'Widget' . (new \ReflectionClass(get_class($this)))->getShortName() . ': ' . Yii::t('easyii', "Required `attribute` param isn\'t set."));
        }

        if(!$this->model->{$this->min}){
            $this->model->{$this->min} = 0;
        }

        if(!$this->model->{$this->max}){
            $this->model->{$this->max} = 100000;
        }
    }

    public function run()
    {
        if (!empty($this->model) && !empty($this->attribute)) {

            $className = (new \ReflectionClass(get_class($this->model)))->getShortName();

            $id = Inflector::slug($className) . '-' . Inflector::slug($this->model->{$this->attribute});

            echo Html::beginTag('div',['class' => 'range-box']);
            echo Html::input('number', ($this->name ? $this->name : $className) . '[min]', $this->model->{$this->min}, ['class' => 'form-control input-'.$this->size, 'min' => $this->model->{$this->min}, 'max' => $this->model->{$this->max}, 'id' => $id . '-from',]);
            echo Html::input('number', ($this->name ? $this->name : $className) . '[max]', $this->model->{$this->max}, ['class' => 'form-control input-'.$this->size, 'min' => $this->model->{$this->min}, 'max' => $this->model->{$this->max}, 'id' => $id . '-to',]);
            echo Html::endTag('div');
            echo Html::tag('div',null,['id' => $id . '-range']);

            $view = $this->view;
            $view->registerAssetBundle(RangeAsset::className());
            $view->registerJs("$(document).ready(function(){
var from = $('#" . $id . "-from'), to = $('#" . $id . "-to'), range = $('#" . $id . "-range');
        
range.range({
    step: " . $this->step . ",
    min: " . $this->model->{$this->min} . ",
    max: " . $this->model->{$this->max} . ",
    value: [" . $this->model->{$this->min} . ", " . $this->model->{$this->max} . "],
    range: " . $this->range . ",
    tooltip: '" . $this->tooltip . "',
    tooltip_split: true,
    tooltip_position: 'bottom'
}).on('slide', function(ev){
    from.val(ev.value[0]);
    to.val(ev.value[1]);
});
to.on('change', function() {
if (!from.val()) from.val(" . $this->model->{$this->min} . ");
    range.range('setValue', [parseInt(from.val()), parseInt($(this).val())]);
});
from.on('change', function() {
if (!to.val()) to.val(" . $this->model->{$this->max} . ");
    range.range('setValue', [parseInt($(this).val()),  parseInt(to.val())]);
});
})",$view::POS_READY,'range-'.$id);
        }
    }
}