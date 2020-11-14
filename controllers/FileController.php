<?php

namespace app\controllers;

use app\jobs\FileParserJob;
use app\models\File;
use app\models\FileSearch;
use app\models\RowSearch;
use app\models\User;
use app\services\FileService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller {

    private ?User $user = null;
    private FileService $fileService;

    public function __construct($id, $module,
            FileService $fileService,
            $config = []) {
        $this->fileService = $fileService;
        $this->user = Yii::$app->user->getIsGuest() ? null : Yii::$app->user->identity->getUser();
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete', 'download', 'upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all File models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FileSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->user_id = $this->user->id;
        $dataProvider = $searchModel->search([]);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'uploadForm' => $this->renderPartial('upload'),
        ]);
    }

    /**
     * Displays a single File model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        if ($model->user_id != $this->user->id) {
            throw new ForbiddenHttpException('У Вас нет доступа к указанному файлу!');
        }
        $searchModel = new RowSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $model,
            'rows' => $this->renderPartial('/row/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]),
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        if ($model->user_id != $this->user->id) {
            throw new ForbiddenHttpException('У Вас нет доступа к указанному файлу!');
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionDownload($id) {
        $model = $this->findModel($id);
        if ($model->user_id != $this->user->id) {
            throw new ForbiddenHttpException('У Вас нет доступа к указанному файлу!');
        }
        $filePath = $this->fileService->getFilePath($model);
        Yii::$app->response->xSendFile($filePath, $model->name, [
            'mimeType' => $model->mime,
            'inline' => true
        ]);
    }

    /**
     * @return array
     */
    public function actionUpload() {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $files = UploadedFile::getInstancesByName('files');
            $response = [
                'initialPreview' => [],
                'initialPreviewConfig' => [],
                'append' => true
            ];
            foreach ($files as $file) {
                $model = new File();
                $model->name = $file->name;
                $model->mime = mime_content_type($file->tempName);
                $model->size = filesize($file->tempName);
                $model->status = File::STATUS_NONE;
                $model->user_id = $this->user->id;

                if ($model->save()) {
                    $filePath = $this->fileService->getFilePath($model);
                    if ($file->saveAs($filePath)) {
                        Yii::$app->queue->push(new FileParserJob(['fileId' => $model->id]));
                    }
                    $downloadUrl = urldecode(Url::to(['/file/download', 'id' => $model->id]));
                    $response['initialPreview'][] = Html::img($downloadUrl);
                    $response['initialPreviewConfig'][] = [
                        'filetype' => $model->mime,
                        'caption' => $model->name,
                        'size' => $model->size,
                        'key' => $model->id,
                        'downloadUrl' => $downloadUrl,
                        'url' => urldecode(Url::to(['/file/delete', 'id' => $model->id])),
                    ];
                }
            }
            return $response;
        }



        return $this->render('upload');
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): File {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }

}
