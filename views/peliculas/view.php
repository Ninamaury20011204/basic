<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url; // <-- IMPORTAR URL

/** @var yii\web\View $this */
/** @var app\models\Peliculas $model */

$this->title = $model->titulo; // <-- Usar título en lugar de ID
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Peliculas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="peliculas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id_peliculas' => $model->id_peliculas, 'actores_id_actores' => $model->actores_id_actores, 'generos_id_generos' => $model->generos_id_generos], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id_peliculas' => $model->id_peliculas, 'actores_id_actores' => $model->actores_id_actores, 'generos_id_generos' => $model->generos_id_generos], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_peliculas',
            'titulo',
            'sinipsis:ntext',
            'anio_lanzamiento',
            'duracion_min',
            // Mostrar nombre del actor
            [
                'attribute' => 'actores_id_actores',
                'value' => $model->actoresIdActores ? $model->actoresIdActores->nombre : 'N/A',
            ],
            // Mostrar nombre del género
            [
                'attribute' => 'generos_id_generos',
                'value' => $model->generosIdGeneros ? $model->generosIdGeneros->nombre : 'N/A',
            ],
            // --- MOSTRAR LA IMAGEN DE PORTADA ---
            [
                'attribute' => 'portada',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->portada) {
                        // Usar Url::to para generar la URL base correcta
                        return Html::img(Url::to('@web/portadas/' . $model->portada), ['width' => '200px']);
                    } else {
                        return 'No hay portada';
                    }
                },
            ],
        ],
    ]) ?>

</div>