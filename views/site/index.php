<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var app\models\Peliculas[] $peliculas */

$this->title = 'NinaApp - Películas';
?>
<style>
    .carousel-item img {
        filter: brightness(0.6);
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
        height: 80vh;
        max-height: 700px;
        object-fit: cover;
        width: 100%;
    }
    .carousel-caption {
        bottom: 40px;
        left: 0;
        right: 0;
        padding: 30px 20px;
        background: linear-gradient(90deg, rgba(0,0,0,0.7) 60%, rgba(0,0,0,0.2) 100%);
        border-radius: 12px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.7);
    }
    .carousel-control-prev, .carousel-control-next {
        width: 60px;
        height: 60px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        border: 2px solid #fff;
        opacity: 0.85;
        z-index: 2;
    }
    .carousel-control-prev {
        left: 30px;
    }
    .carousel-control-next {
        right: 30px;
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-size: 70% 70%;
        filter: invert(1);
    }
    .card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.12);
        overflow: hidden;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
    .card-img-top {
        border-radius: 18px 18px 0 0;
        height: 250px;
        object-fit: cover;
        filter: brightness(0.85);
    }
    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #222;
    }
    .card-text {
        color: #555;
        font-size: 1rem;
    }
</style>
<div class="site-index">
    <!-- Carrusel de portadas -->
    <div id="peliculasCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($peliculas as $i => $pelicula): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <?php if ($pelicula->portada && file_exists(Yii::getAlias('@webroot/portadas/' . $pelicula->portada))): ?>
                        <img src="<?= Url::to('@web/portadas/' . $pelicula->portada) ?>" class="d-block w-100" alt="<?= Html::encode($pelicula->titulo) ?>">
                    <?php else: ?>
                        <div class="d-block w-100 bg-secondary text-white text-center" style="height:80vh;max-height:700px;display:flex;align-items:center;justify-content:center;border-radius:18px;">
                            <span>Sin portada</span>
                        </div>
                    <?php endif; ?>
                    <div class="carousel-caption d-block">
                        <h3 style="font-weight:800;letter-spacing:1px;"><?= Html::encode($pelicula->titulo) ?></h3>
                        <p style="font-size:1.1rem;max-width:600px;margin:auto;opacity:0.85;"> <?= Html::encode($pelicula->sinipsis) ?> </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#peliculasCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#peliculasCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Listado de películas -->
    <div class="row">
        <?php foreach ($peliculas as $pelicula): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($pelicula->portada && file_exists(Yii::getAlias('@webroot/portadas/' . $pelicula->portada))): ?>
                        <img src="<?= Url::to('@web/portadas/' . $pelicula->portada) ?>" class="card-img-top" alt="<?= Html::encode($pelicula->titulo) ?>">
                    <?php else: ?>
                        <div class="card-img-top bg-secondary text-white text-center" style="height:250px;display:flex;align-items:center;justify-content:center;">
                            <span>Sin portada</span>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($pelicula->titulo) ?></h5>
                        <p class="card-text mb-1">Año: <span style="font-weight:600; color:#333;"><?= Html::encode($pelicula->anio_lanzamiento) ?></span></p>
                        <p class="card-text mb-1">Duración: <span style="font-weight:600; color:#333;"><?= Html::encode($pelicula->duracion_min) ?> min</span></p>
                        <p class="card-text">Sinopsis: <span style="color:#444;"><?= Html::encode($pelicula->sinipsis) ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
        </div>

    </div>
</div>
