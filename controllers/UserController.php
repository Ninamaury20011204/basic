<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\ChangePasswordForm;


class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete', 'reset-password', 'change-password'],
                'rules' => [ // <-- ¡Este es el array principal que contendrá TODAS las reglas!

                    // REGLA 1 (Para 'change-password')
                    [
                        'allow' => true,
                        'actions' => ['change-password'], // Usar 'actions' en plural
                        'roles' => ['@'] // Permitir a cualquier usuario autenticado
                    ],

                    // REGLA 2 (Para Administradores)
                    [
                        'allow' => true,
                        // Dejar 'actions' vacío o no incluirlo significa que aplica a TODAS las acciones en 'only'
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'reset-password'], // Lista de acciones que el admin puede hacer
                        'roles' => ['@'], // Debe estar autenticado
                        'matchCallback' => function ($rule, $action) {
                            // ¡IMPORTANTE! También debes añadir la verificación isGuest aquí para evitar un error de 'null' si no lo hiciste en el layout.
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin';
                        }
                    ]
                ], // <-- ¡FIN DEL ARRAY PRINCIPAL DE REGLAS!
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ]
            ]
        ];
    }
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionResetePassword($id)
    {
        $user = $this->findModel($id);
        $user->password = $user->username;
        if ($user->save()) {
            Yii::$app->session->setFlash('succes', 'Password reset succesfully');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to reset password');
        }

        return $this->redirect(['index']);
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Password change successfully');
            return $this->redirect(['index']);
        }
        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist');

    }
}