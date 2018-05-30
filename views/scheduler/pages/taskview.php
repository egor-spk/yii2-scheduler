<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model spk\scheduler\models\SchedulerTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-view">
    <br>
    <h3><?= Html::encode($model->command) ?></h3>
    <?= Html::a('Logs', '#',
        [
            'title' => 'Delete',
            'class' => 'btn btn-default',
            'onclick' => "Scheduler.view.onLogs($model->id)",
        ]) ?>
    &nbsp;&nbsp;
    <?= Html::a('Update', '#',
        [
            'title' => 'Delete',
            'class' => 'btn btn-primary',
            'onclick' => "Scheduler.view.onEdit($model->id)",
        ]) ?>
    <?= Html::a('Delete', '#',
        [
            'title' => 'Delete',
            'class' => 'btn btn-danger',
            'onclick' => "Scheduler.view.onDelete($model->id)",
        ]) ?>
    <br>
    <br>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'command',
            'comment',
            'statusName',
        ],
    ]) ?>
    <label for="cron-time">Cron time</label>
    <pre>
    <input id="cron-time" type="text" class="form-control" value="<?= $model->cron_time ?>" readonly>
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
</div>
<script>
    Scheduler.view.init();
</script>