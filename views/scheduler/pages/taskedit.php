<?php

use spk\scheduler\models\SchedulerStatus;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model spk\scheduler\models\SchedulerTask */
?>
<?php Pjax::begin([
    'id' => uniqid(),
    'timeout' => 5000,
    'enablePushState' => false,
]); ?>
<div class="task-edit">
    <br>
    <?php if ($model->isNewRecord): ?>
        <h3>Create new scheduler task</h3>
    <?php else: ?>
        <h3>Update <?= Html::encode($model->command) ?></h3>
    <?php endif; ?>

    <div class="form-group">
        <label class="control-label" for="schedulertask-method">Method</label>
        <?= Html::dropDownList('method', null, $methods, [
            'id' => 'schedulertask-method',
            'class' => 'form-control',
            'prompt' => 'Select method',
        ]) ?>
        <div class="help-block"></div>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'taskedit-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <?= $form->field($model, 'command')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_id')->dropDownList([
        SchedulerStatus::ON => 'On',
        SchedulerStatus::OFF => 'Off'
    ], [
        'prompt' => 'Select status'
    ])->label('Status') ?>

    <?= $form->field($model, 'cron_time')->textInput(['placeholder' => '* * * * *', 'maxlength' => true]) ?>
    <pre>
* * * * *
- - - - -
| | | | |
| | | | |
| | | | +----- day of week (0 - 7) (Sunday=0 or 7)
| | | +------- month (1 - 12)
| | +--------- day of month (1 - 31)
| +----------- hour (0 - 23)
+------------- min (0 - 59)
    </pre>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
<script>
    Scheduler.edit.init();
</script>