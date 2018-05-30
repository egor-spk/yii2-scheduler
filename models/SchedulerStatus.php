<?php

namespace spk\scheduler\models;

/**
 * This is the model class for table "scheduler_status".
 *
 * @property integer $id
 * @property string $name
 *
 * @property SchedulerTask[] $schedulerTasks
 * @property SchedulerTaskLog[] $schedulerTaskLogs
 */
class SchedulerStatus extends \yii\db\ActiveRecord
{
    const ON = 1;
    const OFF = 2;
    const SUCCESS = 3;
    const ERROR = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduler_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulerTasks()
    {
        return $this->hasMany(SchedulerTask::className(), ['status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulerTaskLogs()
    {
        return $this->hasMany(SchedulerTaskLog::className(), ['status_id' => 'id']);
    }
}
