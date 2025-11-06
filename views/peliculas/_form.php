<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Actores;
use app\models\Generos;
use yii\helpers\Url; // <-- Importante para la imagen

/** @var yii\web\View $this */
/** @var app\models\Peliculas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="peliculas-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'titulo')->textInput([
        'maxlength' => true,
        'placeholder' => 'Ej: El Padrino' // <-- Placeholder
    ]) ?>

    <?= $form->field($model, 'sinipsis')->textarea([
        'rows' => 6,
        'placeholder' => 'Ej: La historia de la familia Corleone...' // <-- Placeholder
    ]) ?>

    <?= $form->field($model, 'anio_lanzamiento')->textInput([
        'type' => 'date' // <-- CAMBIO: Esto crea un selector de fecha (YYYY-MM-DD)
    ]) ?>

    <?= $form->field($model, 'duracion_min')->textInput([
        'type' => 'number', // <-- CAMBIO: Solo permite números
        'min' => 1,
        'placeholder' => 'Ej: 175 (en minutos)' // <-- Placeholder
    ]) ?>

    <?php
    // Dropdown para Actores
    $actores = ArrayHelper::map(Actores::find()->all(), 'id_actores', 'nombre');
    echo $form->field($model, 'actores_id_actores')->dropDownList(
        $actores,
        ['prompt' => 'Seleccione un Actor'] // <-- El 'prompt' actúa como placeholder
    );
    ?>

    <?php
    // Dropdown para Generos
    $generos = ArrayHelper::map(Generos::find()->all(), 'id_generos', 'nombre');
    echo $form->field($model, 'generos_id_generos')->dropDownList(
        $generos,
        ['prompt' => 'Seleccione un Género'] // <-- El 'prompt' actúa como placeholder
    );
    ?>

    <?php
    // Campo para subir la imagen
    echo $form->field($model, 'imagenFile')->fileInput();

    // Mostrar la imagen actual si estamos en 'update' y ya existe una
    if (!$model->isNewRecord && $model->portada && file_exists(Yii::getAlias('@webroot/portadas/' . $model->portada))) {
        echo '<div class="form-group">';
        echo Html::label(Yii::t('app', 'Portada Actual'));
        echo '<br />';
        // Usamos Url::to para asegurar la ruta correcta
        echo Html::img(Url::to('@web/portadas/' . $model->portada), ['width' => '150px']);
        echo '</div>';
    }
    ?>

    <div class="form-group mt-3"> <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>