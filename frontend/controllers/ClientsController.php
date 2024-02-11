<?php

namespace frontend\controllers;

use Yii;
use frontend\models\ClientsForm;
use frontend\models\ClientsSearch;
use frontend\models\ClientToClubs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ClientsController implements the CRUD actions for Clients model.
 */
class ClientsController extends Controller
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
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Clients models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClientsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clients model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Clients model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $model = new ClientsForm();
    
    if ($model->load($this->request->post())) {
        $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');

        if ($model->save()) {
            if ($model->avatarFile) {
                $uploadPath = Yii::getAlias('@frontend/web/avatars/');
                $fileName = 'avatar_' . Yii::$app->security->generateRandomString(10) . '.' . $model->avatarFile->extension;
                $filePath = $uploadPath . $fileName;

                if ($model->avatarFile->saveAs($filePath)) {
                    $model->avatar = 'avatars/' . $fileName;
                    $model->save(false);
                } else {
                    Yii::error('Failed to save the uploaded file.');
                }
            }

            if (!empty($model->client_clubs)) {
                foreach ($model->client_clubs as $clubId) 
                {
                    $clientToClub = new ClientToClubs();
                    $clientToClub->client_id = $model->id;
                    $clientToClub->club_id = $clubId;
                    if (!$clientToClub->save())
                    {
                        return 'Error while saving client to club with ID: ' . $clubId;
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing Clients model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');
            if ($model->avatarFile) {
                $uploadPath = Yii::getAlias('@frontend/web/avatars/');
                $fileName = 'avatar_' . Yii::$app->security->generateRandomString(10) . '.' . $model->avatarFile->extension;
                $filePath = $uploadPath . $fileName;

                if ($model->avatarFile->saveAs($filePath)) {
                    $model->avatar = 'avatars/' . $fileName;
                    $model->save(false);
                } else {
                    Yii::error('Failed to save the uploaded file.');
                }
            }

            ClientToClubs::deleteAll(['client_id' => $model->id]);
            if (!empty($model->client_clubs)) {
                foreach ($model->client_clubs as $clubId) {
                    $clientToClub = new ClientToClubs();
                    $clientToClub->client_id = $model->id;
                    $clientToClub->club_id = $clubId; 
                    if (!$clientToClub->save()) {
                        return 'Error while saving client to club with ID: ' . $clubId;
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Clients model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted_at = time();
        $model->deleted_by = Yii::$app->user->id;
        $model->update(false);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Clients model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Clients the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClientsForm::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}