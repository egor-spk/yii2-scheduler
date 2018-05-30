<?php

use kartik\date\DatePicker;
use spk\scheduler\models\SchedulerStatus;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel spk\scheduler\models\SchedulerTaskLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="log-list">
    <br>
    <?php // If set SchedulerTaskLog id, then disable delete from range
    if (isset($searchModel->task_id)): ?>
        <h3><?= Html::encode('Logs for ' . $searchModel->task->command) ?></h3>
    <?php else: ?>
        <?php $datePickerLayout = <<< HTML
        <span class="input-group-addon">From</span>
        {input1}
        {separator}
        <span class="input-group-addon">To</span>
        {input2}
        <span class="input-group-addon kv-date-remove">
            <i class="glyphicon glyphicon-remove"></i>
        </span>
HTML;
        ?>
        <div class="row">
            <div class="col-md-10">
                <?php ActiveForm::begin([
                    'id' => 'date-range'
                ]);
                echo DatePicker::widget([
                    'type' => DatePicker::TYPE_RANGE,
                    'name' => 'from',
                    'value' => date('Y-m-d'),
                    'name2' => 'to',
                    'value2' => date('Y-m-d'),
                    'separator' => '<i class="glyphicon glyphicon-minus"></i>',
                    'layout' => $datePickerLayout,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'weekStart' => 1
                    ]
                ]); ?>
            </div>
            <div class="col-md-2 text-right">
                <?php echo Html::a('Delete', '#', [
                    'class' => 'btn btn-danger',
                    'onclick' => "Scheduler.logs.onDelete()",
                ]);
                ActiveForm::end();
                ?>
            </div>
        </div>
        <br>
    <?php endif; ?>
    <?php Pjax::begin([
        'id' => 'grid-pjax',
        'timeout' => 5000,
        'enablePushState' => false,
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive', 'id' => 'logs-gridview'],
        'tableOptions' =>
            [
                'class' => 'table table-bordered table-hover'
            ],
        'columns' => [
            [
                'attribute' => 'start_date_time',
                'label' => 'Started',
                'value' => 'start_date_time',
                'headerOptions' => [
                    'style' => 'width:190px'
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'start_date_time',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'weekStart' => 1
                    ]
                ])
            ],
            [
                'attribute' => 'task_command',
                'content' => function ($model) {
                    /* @var $model spk\scheduler\models\SchedulerTaskLog */
                    return '<a href="#" onclick="Scheduler.logs.onTaskView(' . $model->task_id . ')" data-pjax="0">' . $model->task_command . '</a>';
                },
            ],
            [
                'attribute' => 'status_id',
                'label' => 'Status',
                'content' => function ($model) {
                    /* @var $model spk\scheduler\models\SchedulerTaskLog */
                    if ($model->status_id == SchedulerStatus::SUCCESS) {
                        $color = 'color: green;';
                    } else {
                        $color = 'color: red;';
                    }
                    return '<span style="' . $color . '">' . $model->status->name . '</span>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'status_id', [
                    SchedulerStatus::SUCCESS => 'Success',
                    SchedulerStatus::ERROR => 'Error'
                ], [
                    'class' => 'form-control',
                    'prompt' => 'Select status'
                ])
            ],
            'execution_time',
            [
                'attribute' => 'output',
                'label' => 'Output',
                'contentOptions' => ['style' => 'width: 10px; text-align: center'],
                'content' => function ($model) {
                    /* @var $model spk\scheduler\models\SchedulerTaskLog */
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                        '#',
                        [
                            'title' => 'View',
                            'onclick' => "Scheduler.logs.onView($model->id)",
                        ]);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<div class="modal fade" id="output-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Output</h4>
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
    Scheduler.logs.init();
</script>