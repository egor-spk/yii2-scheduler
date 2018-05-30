<?php

use yii\helpers\Html;

$this->title = 'Task scheduler';
?>
<div id="yii2-scheduler">
    <h2><?= Html::encode($this->title) ?></h2>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="tasks" aria-controls="tasks" role="tab" data-toggle="tab">Task list</a>
        </li>
        <li role="presentation">
            <a href="task-create" aria-controls="task-create" role="tab" data-toggle="tab">Add/Edit task</a>
        </li>
        <li role="presentation">
            <a href="logs" aria-controls="logs" role="tab" data-toggle="tab">Logs</a>
        </li>
    </ul>
    <div class="tab-content">
    </div>
    <div class="spinner" style="display: none"></div>
</div>
<script>
    window.onload = function (ev) {
        Scheduler.common.init();
    }
</script>