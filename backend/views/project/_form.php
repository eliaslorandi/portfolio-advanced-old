<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\editors\Summernote;

/** @var yii\web\View $this */
/** @var common\models\Project $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); //['options' => ['enctype' => 'multipart/form-data']]
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tech_stack')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::class, [
        //'language' => 'ru',
        //'dateFormat' => 'yyyy-MM-dd',  //pode ser removido pois em common foi setado o date padrão
        'options' => ['readOnly' => true], //usuario não poderá escrever a data, apenas selecionar
    ]) ?>

    <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::class, [
        //'language' => 'ru',
        //'dateFormat' => 'yyyy-MM-dd',
        'options' => ['readOnly' => true], //usuario não poderá escrever a data, apenas selecionar
    ]) ?>

    <?php foreach ($model->images as $image) ?>

    <?= $form->field($model, 'imageFile')->fileInput() //versoes antes de 2.0.8 tem que add uo comentado
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>