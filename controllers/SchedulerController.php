<?php


namespace spk\scheduler\controllers;

use spk\scheduler\assets\AppAssets;
use spk\scheduler\models\MethodsLoader;
use spk\scheduler\models\SchedulerTask;
use spk\scheduler\models\SchedulerTaskLog;
use spk\scheduler\models\SchedulerTaskLogSearch;
use spk\scheduler\models\SchedulerTaskSearch;
use spk\scheduler\models\TaskRunner;
use yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class SchedulerController extends Controller
{
    public $defaultAction = 'index';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'task-delete' => ['POST'],
                    'logs-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Main scheduler page
     * @return mixed
     */
    public function actionIndex()
    {
        $view = $this->getView();
        AppAssets::register($view);

        return $this->render('index');
    }

    /**
     * Lists all SchedulerTask models
     * @return mixed
     */
    public function actionTasks()
    {
        if (Yii::$app->request->isAjax) {
            $searchModel = new SchedulerTaskSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax("pages/tasks", [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Delete task model
     * @return mixed
     */
    public function actionTaskDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            if (($model = SchedulerTask::findOne($id)) !== null) {
                if ($model->delete() === false) {
                    throw new ServerErrorHttpException("Unable delete task");
                }
                return;
            }

            throw new NotFoundHttpException("The task with id=$id does not exist.");
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Lists all SchedulerTaskLog models
     * @return mixed
     */
    public function actionLogs($task_id = null)
    {
        if (Yii::$app->request->isAjax) {
            $searchModel = new SchedulerTaskLogSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax("pages/logs", [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Get SchedulerTaskLog output string
     * @return string
     */
    public function actionLogOutput($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = SchedulerTaskLog::findOne(['id' => $id]);

            if ($model === null) {
                throw new NotFoundHttpException("Unknown task log model with id=$id");
            }

            return $model->output;
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Delete SchedulerTaskLog models from range
     * @return mixed
     */
    public function actionLogsDelete()
    {
        if (Yii::$app->request->isAjax) {
            return SchedulerTaskLog::deleteRange(
                Yii::$app->request->post('from'),
                Yii::$app->request->post('to'));
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Update SchedulerTask model
     * @param integer $id
     * @return mixed
     */
    public function actionTaskUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = SchedulerTask::findOne(['id' => $id]);

            if ($model === null) {
                throw new NotFoundHttpException("Unknown task model with id=$id");
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->renderAjax("pages/taskview", [
                    'model' => $model
                ]);
            }

            $methods = MethodsLoader::getAllMethods(
                Yii::$app->controller->module->defaultFolder,
                Yii::$app->controller->module->folderDepth);
            return $this->renderAjax("pages/taskedit", [
                'model' => $model,
                'methods' => $methods
            ]);
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Create SchedulerTask model
     * @return mixed
     */
    public function actionTaskCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new SchedulerTask();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->renderAjax("pages/taskview", [
                    'model' => $model
                ]);
            }

            $methods = MethodsLoader::getAllMethods(
                Yii::$app->controller->module->defaultFolder,
                Yii::$app->controller->module->folderDepth);
            return $this->renderAjax("pages/taskedit", [
                'model' => $model,
                'methods' => $methods
            ]);
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Displays a single SchedulerTask model
     * @param integer $id
     * @return mixed
     */
    public function actionTaskView($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = SchedulerTask::findOne(['id' => $id]);

            if ($model === null) {
                throw new NotFoundHttpException("Unknown task model with id=$id");
            }

            return $this->renderAjax("pages/taskview", [
                'model' => $model
            ]);
        } else {
            throw new BadRequestHttpException("Only ajax allowed");
        }
    }

    /**
     * Run a single SchedulerTask
     * @param integer $id
     * @return mixed
     */
    public function actionRunTask($id)
    {
        $runner = new TaskRunner();
        $status = $runner->RunSingleTask($id);

        header('Content-Type: application/json');
        return json_encode([
            'status' => $status,
            'output' => $runner->output
        ]);
    }
}