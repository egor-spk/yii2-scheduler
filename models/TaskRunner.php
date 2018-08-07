<?php

namespace spk\scheduler\models;

use DateTime;
use Exception;
use yii;
use yii\base\Model;

class TaskRunner extends Model
{
    public $output;
    private $startDateTime;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->startDateTime = new DateTime(date('Y-m-d H:i:s'));
    }

    /**
     * Run several tasks
     * @return bool|int true - success execute all tasks. 0 if not one task is not executed
     */
    public function RunAllTasks()
    {
        $tasks = SchedulerTask::find()->all();
        if (empty($tasks)) {
            $this->output .= "Any task is not found\n";
            return false;
        } else {
            $results = [];
            foreach ($tasks as $task) {
                if ($task->canStart()) {
                    $results[] = $this->RunTask($task);
                }
            }

            // If at least one of tasks was executed with a error, then the general result is false
            if (in_array(false, $results)) {
                return false;
            } elseif (empty($results)) {
                return 0;
            } else {
                return true;
            }
        }
    }

    /**
     * Execute a task
     * @param SchedulerTask $task
     * @return bool
     */
    private function RunTask($task)
    {
        $this->output = '';
        try {
            $class = $this->getClass($task->command);
            $method = $this->getMethod($task->command);

            ob_start();
            $res = Invoker::invokeMethod($class, $method);
            $this->output .= ob_get_clean() . "\n";

            if ($res === true) {
                $this->writeResult($task->id, SchedulerStatus::SUCCESS);
                return true;
            } elseif ($res === false) {
                $this->writeResult($task->id, SchedulerStatus::ERROR);
                return false;
            } else {
                if (!isset($res)) {
                    $this->output .= "The command returned nothing\n";
                } else {
                    $this->output .= "The command returned:\n" . $res;
                }

                $this->writeResult($task->id, SchedulerStatus::ERROR);
                return false;
            }
        } catch (Exception $e) {
            $this->output .= ob_get_clean() . "\n";
            $this->output .= 'Caught an exception: ' . get_class($e) . ":\n" . $e->getMessage() . "\n";
            $this->writeResult($task->id, SchedulerStatus::ERROR);
            return false;
        }
    }

    /**
     * Get class name from command
     * @param $command
     * @return string
     */
    private function getClass($command)
    {
        $CLASS_POSITION = 0;
        if ($res = preg_split("/::/", $command)) {
            return $res[$CLASS_POSITION];
        } else {
            return false;
        }
    }

    /**
     * Get method name from command
     * @param $command
     * @return string
     */
    private function getMethod($command)
    {
        $METHOD_POSITION = 1;
        if ($res = preg_split("/::/", $command)) {
            return $res[$METHOD_POSITION];
        } else {
            return false;
        }
    }

    /**
     * Write result work of a task
     * @param int $id task id
     * @param SchedulerStatus $status
     */
    private function writeResult($id, $status)
    {
        $log = new SchedulerTaskLog();
        $log->status_id = $status;
        $log->start_date_time = $this->startDateTime->format('Y-m-d H:i:s');
        $log->task_id = $id;
        $log->execution_time = $this->getExecutionTime();
        $log->output = $this->output;
        if (!$log->save()) {
            Yii::error('Unable write task log in db');
        }
    }

    /**
     * Get duration from $this->startDateTime to current time
     * @return string time in format 'H:i:s'
     */
    private function getExecutionTime()
    {
        $curDateTime = new DateTime(date('Y-m-d H:i:s'));
        $duration = $this->startDateTime->diff($curDateTime);
        return $duration->format('%H:%i:%s');
    }

    /**
     * Run single task
     * @param int $id task id
     * @return bool true - success execute task
     */
    public function RunSingleTask($id)
    {
        $task = SchedulerTask::findOne(['id' => $id]);
        if ($task === null) {
            $this->output .= "Task with id=$id not found\n";
            return false;
        } else {
            return $this->RunTask($task);
        }
    }
}