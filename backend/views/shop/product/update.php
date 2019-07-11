<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $product cabinet\entities\shop\product\Product */
/* @var $model cabinet\forms\manage\shop\product\ProductForm */

$this->title = 'Редактировать товар: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="product-update">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'
    ]]); ?>

    <div class="box box-default">
        <div class="box-header with-border">Основное</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'sort')->textInput(['value' => 1]) ?>
                    <?= $form->field($model, 'race_id')->dropDownList($model->getRaces()) ?>
                </div>
            </div>
            <?= $form->field($model, 'description')->textArea() ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Фотография</div>
        <div class="box-body">
            <?= $form->field($model, 'photo')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => false,
                ]
            ])->label(false) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
