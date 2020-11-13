<?php

namespace app\controllers;

use app\services\FileService;
use Yii;
use app\models\File;
use app\models\FileSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{
    private FileService $fileService;

    public function __construct($id, $module,
                                FileService $fileService,
                                $config = []) {
        $this->fileService = $fileService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all File models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single File model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $filePath = $this->fileService->getFilePath($model);
        Yii::$app->response->xSendFile($filePath, $model->name, [
            'mimeType' => $model->mime,
            'inline' => true
        ]);
    }

    /**
     * @return array
     */
    public function actionUpload()
    {
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
                $model->status = File::STATUS_WAIT;

                if ($model->save()) {
                    $filePath = $this->fileService->getFilePath($model);
                    $file->saveAs($filePath);
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
    protected function findModel($id): File
    {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }
}
