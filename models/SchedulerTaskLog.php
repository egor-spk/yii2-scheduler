<?php

namespace spk\scheduler\models;

use yii\helpers\ArrayHelper;
use yii;

/**
 * This is the model class for table "scheduler_task_log".
 *
 * @property integer $id
 * @property string $start_date_time
 * @property string $execution_time
 * @property string $output
 * @property integer $task_id
 * @property integer $status_id
 *
 * @property SchedulerStatus $status
 * @property SchedulerTask $task
 */
class SchedulerTaskLog extends \yii\db\ActiveRecord
{
    public $task_command;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduler_task_log';
    }

    /**
     * Delete logs by range
     * @param $from
     * @param $to
     * @return int count deleted row
     */
    public static function deleteRange($from, $to)
    {
        return static::deleteAll(['BETWEEN', 'DATE(start_date_time)', $from, $to]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_date_time', 'execution_time', 'task_id'], 'required'],
            [['start_date_time', 'execution_time'], 'safe'],
            [['output'], 'string'],
            [['task_id', 'status_id'], 'integer'],
            [
                ['status_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SchedulerStatus::className(),
                'targetAttribute' => ['status_id' => 'id']
            ],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SchedulerTask::className(),
                'targetAttribute' => ['task_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_date_time' => 'Start date time',
            'execution_time' => 'Duration',
            'output' => 'Output',
            'task_id' => 'Task ID',
            'status_id' => 'Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(SchedulerStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(SchedulerTask::className(), ['id' => 'task_id']);
    }
}
