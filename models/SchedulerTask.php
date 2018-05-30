<?php

namespace spk\scheduler\models;

use Cron\CronExpression;

/**
 * This is the model class for table "scheduler_task".
 *
 * @property integer $id
 * @property string $cron_time
 * @property string $command
 * @property string $comment
 * @property integer $status_id
 *
 * @property SchedulerStatus $status
 * @property SchedulerTaskLog[] $schedulerTaskLogs
 */
class SchedulerTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduler_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cron_time', 'command', 'status_id'], 'required'],
            [['status_id'], 'integer'],
            [
                'cron_time',
                function ($attribute, $params, $validator) {
                    if (!preg_match(
                        "/(\*|[0-5]?[0-9]|\*\/[0-9]+)\s+"
                        . "(\*|1?[0-9]|2[0-3]|\*\/[0-9]+)\s+"
                        . "(\*|[1-2]?[0-9]|3[0-1]|\*\/[0-9]+)\s+"
                        . "(\*|[0-9]|1[0-2]|\*\/[0-9]+|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s+"
                        . "(\*\/[0-9]+|\*|[0-7]|sun|mon|tue|wed|thu|fri|sat)\s*"
                        . "(\*\/[0-9]+|\*|[0-9]+)?/i", $this->cron_time, $matches)) {
                        $this->addError($attribute, 'Bad cron time expression format');
                    }
                }
            ],
            [['command', 'comment'], 'string', 'max' => 256],
            [
                ['status_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SchedulerStatus::className(),
                'targetAttribute' => ['status_id' => 'id']
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
            'cron_time' => 'Cron time',
            'command' => 'Command',
            'comment' => 'Comment',
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
    public function getSchedulerTaskLogs()
    {
        return $this->hasMany(SchedulerTaskLog::className(), ['task_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->status->name;
    }

    /**
     * Task enable and there came time for start of a task
     * @return bool
     */
    public function canStart()
    {
        if($this->status === SchedulerStatus::OFF)
            return false;

        $cron = CronExpression::factory($this->cron_time);
        return $cron->isDue(date('Y-m-d H:i:s'));
    }
}
