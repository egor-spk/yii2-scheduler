<?php

use spk\scheduler\models\SchedulerStatus;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel spk\scheduler\models\SchedulerTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="task-list">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Add new task', '#', [
            'onclick' => "Scheduler.tasks.onCreate()",
            'class' => 'btn btn-success'
        ]) ?>
    </p>
    <?php Pjax::begin([
        'id' => 'grid-pjax',
        'timeout' => 5000,
        'enablePushState' => false,
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive', 'id' => 'tasks-gridview'],
        'layout' => "{items}\n{pager}",
        'tableOptions' =>
            [
                'class' => 'table table-bordered table-hover',
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'command',
            'cron_time',
            'comment',
            [
                'attribute' => 'status_id',
                'label' => 'Status',
                'content' => function ($model) {
                    /* @var $model spk\scheduler\models\SchedulerTask */
                    if ($model->status_id == SchedulerStatus::ON) {
                        $color = 'color: green;';
                    } else {
                        $color = 'color: red;';
                    }
                    return '<span style="' . $color . '">' . $model->status->name . '</span>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'status_id', [
                    SchedulerStatus::ON => 'On',
                    SchedulerStatus::OFF => 'Off'
                ], [
                    'class' => 'form-control',
                    'prompt' => 'Select status'
                ])
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'contentOptions' => ['style' => 'width: 10px'],
                'template' => '<span style="white-space: nowrap">{run} {view} {edit} {log} {delete}</span>',
                'buttons' => [
                    'run' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-play"></span>',
                            '#',
                            [
                                'title' => 'Run',
                                'onclick' => "Scheduler.tasks.onRun($model->id)",
                            ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '#',
                            [
                                'title' => 'View',
                                'onclick' => "Scheduler.tasks.onView($model->id)",
                            ]);
                    },
                    'edit' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '#',
                            [
                                'title' => 'Edit',
                                'onclick' => "Scheduler.tasks.onEdit($model->id)",
                            ]);
                    },
                    'log' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list"></span>',
                            '#',
                            [
                                'title' => 'Logs',
                                'onclick' => "Scheduler.tasks.onLog($model->id)",
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '#',
                            [
                                'title' => 'Delete',
                                'onclick' => "Scheduler.tasks.onDelete($model->id)",
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<div class="modal fade" id="run-task-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Run task</h4>
            </div>
            <div class="modal-body">
                <textarea readonly class="form-control" rows="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    Scheduler.tasks.init();
</script>