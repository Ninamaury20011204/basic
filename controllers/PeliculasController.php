<?php

namespace app\controllers;

use app\models\Peliculas;
use app\models\PeliculasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile; // <-- IMPORTAR CLASE
use Yii; // <-- IMPORTAR YII

/**
 * PeliculasController implements the CRUD actions for Peliculas model.
 */
class PeliculasController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    // ... (actionIndex y actionView se quedan igual) ...

    /**
     * Lists all Peliculas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PeliculasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Peliculas model.
     * @param int $id_peliculas Id Peliculas
     * @param int $actores_id_actores Actores Id Actores
     * @param int $generos_id_generos Generos Id Generos
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_peliculas, $actores_id_actores, $generos_id_generos)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_peliculas, $actores_id_actores, $generos_id_generos),
        ]);
    }

    /**
     * Creates a new Peliculas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Peliculas();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                // --- CAPTURAR LA IMAGEN ---
                $model->imagenFile = UploadedFile::getInstance($model, 'imagenFile');
                
                // Intentar subir la imagen si existe
                if ($model->imagenFile) {
                    if (!$model->upload()) {
                        // Hubo un error al subir
                        Yii::$app->session->setFlash('error', 'Error al subir la imagen.');
                        $model->addError('imagenFile', 'Error al procesar el archivo.');
                        // No guardamos el modelo si la subida falla
                        return $this->render('create', ['model' => $model]);
                    }
                    // 'portada' ya fue asignado en el método upload()
                }

                // Guardar el modelo (con o sin portada)
                if ($model->save(false)) { // Usamos save(false) para saltar validación (ya se hizo en load y upload)
                                        // O mejor, quita el 'false' si quieres que re-valide todo
                // if ($model->save()) { // <--- MEJOR ASÍ
                    Yii::$app->session->setFlash('success', 'Película creada exitosamente.');
                    return $this->redirect(['view', 'id_peliculas' => $model->id_peliculas, 'actores_id_actores' => $model->actores_id_actores, 'generos_id_generos' => $model->generos_id_generos]);
                } else {
                     Yii::$app->session->setFlash('error', 'No se pudo guardar la película. Revise los errores.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Peliculas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_peliculas Id Peliculas
     * @param int $actores_id_actores Actores Id Actores
     * @param int $generos_id_generos Generos Id Generos
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_peliculas, $actores_id_actores, $generos_id_generos)
    {
        $model = $this->findModel($id_peliculas, $actores_id_actores, $generos_id_generos);
        
        // Guardar el nombre de la portada vieja
        $oldPortada = $model->portada;

        if ($this->request->isPost && $model->load($this->request->post())) {
            
            // --- CAPTURAR LA NUEVA IMAGEN ---
            $model->imagenFile = UploadedFile::getInstance($model, 'imagenFile');
            
            $fileUploaded = false;
            if ($model->imagenFile) {
                // Si se subió un archivo nuevo, intentar subirlo
                if ($model->upload($oldPortada)) { // Pasamos la portada vieja para que la borre
                    $fileUploaded = true;
                } else {
                    // Hubo un error al subir
                    Yii::$app->session->setFlash('error', 'Error al subir la nueva imagen.');
                    $model->addError('imagenFile', 'Error al procesar el archivo.');
                    return $this->render('update', ['model' => $model]);
                }
            }

            // Si no se subió un archivo nuevo, mantenemos la portada anterior
            if (!$fileUploaded && !$model->imagenFile) {
                $model->portada = $oldPortada;
            }

            // Guardar el modelo
            if ($model->save()) { // save() validará los demás campos
                Yii::$app->session->setFlash('success', 'Película actualizada exitosamente.');
                return $this->redirect(['view', 'id_peliculas' => $model->id_peliculas, 'actores_id_actores' => $model->actores_id_actores, 'generos_id_generos' => $model->generos_id_generos]);
            } else {
                 Yii::$app->session->setFlash('error', 'No se pudo actualizar la película. Revise los errores.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Peliculas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_peliculas Id Peliculas
     * @param int $actores_id_actores Actores Id Actores
     * @param int $generos_id_generos Generos Id Generos
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_peliculas, $actores_id_actores, $generos_id_generos)
    {
        // El método afterDelete en el modelo se encargará de borrar el archivo
        $this->findModel($id_peliculas, $actores_id_actores, $generos_id_generos)->delete();
        Yii::$app->session->setFlash('success', 'Película eliminada exitosamente.');

        return $this->redirect(['index']);
    }

    // ... (findModel se queda igual) ...
        /**
     * Finds the Peliculas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_peliculas Id Peliculas
     * @param int $actores_id_actores Actores Id Actores
     * @param int $generos_id_generos Generos Id Generos
     * @return Peliculas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_peliculas, $actores_id_actores, $generos_id_generos)
    {
        if (($model = Peliculas::findOne(['id_peliculas' => $id_peliculas, 'actores_id_actores' => $actores_id_actores, 'generos_id_generos' => $generos_id_generos])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}