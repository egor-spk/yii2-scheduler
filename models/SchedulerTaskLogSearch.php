<?php

namespace spk\scheduler\models;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SchedulerTaskLogSearch represents the model behind the search form of `spk\scheduler\models\SchedulerTaskLog`.
 */
class SchedulerTaskLogSearch extends SchedulerTaskLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'status_id'], 'integer'],
            [['start_date_time', 'task_command'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $tasks = SchedulerTask::find();
        $query = SchedulerTaskLog::find()
            ->alias('logs')
            ->innerJoin(['task' => $tasks], 'task_id = task.id')
            ->addSelect([
                'logs.id',
                'logs.start_date_time',
                'logs.execution_time',
                'logs.status_id',
                'task.command as task_command',
                'task_id'
            ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->setSort([
            'defaultOrder' => ['start_date_time' => SORT_DESC],
            'attributes' => [
                'start_date_time',
                'execution_time'
            ]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'DATE(start_date_time)' => $this->start_date_time,
            'logs.status_id' => $this->status_id,
            'logs.task_id' => $this->task_id,
        ]);

        $query->andFilterWhere(['like', 'task.command', $this->task_command]);

        return $dataProvider;
    }

    public function formName()
    {
        return '';
    }
}
