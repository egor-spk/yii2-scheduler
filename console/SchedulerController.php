<?php

namespace spk\scheduler\console;


use spk\scheduler\models\TaskRunner;
use yii\console\Controller;

class SchedulerController extends Controller
{
    /**
     * Execute all tasks considering cron time
     */
    public function actionRun()
    {
        $runner = new TaskRunner();
        if ($res = $runner->RunAllTasks()) {
            echo "Success\n";
        } elseif ($res === 0) {
            echo "0 tasks are executed\n";
        } else {
            echo "Error. Details:\n" . $runner->output;
        }
    }
}