<?php

namespace spk\scheduler\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use spk\scheduler\models\SchedulerTask;

/**
 * SchedulerTaskSearch represents the model behind the search form of `spk\scheduler\models\SchedulerTask`.
 */
class SchedulerTaskSearch extends SchedulerTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id'], 'integer'],
            [['cron_time', 'command', 'comment'], 'safe'],
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
        $query = SchedulerTask::find()->addOrderBy(['id' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'cron_time', $this->cron_time])
            ->andFilterWhere(['like', 'command', $this->command])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }

    public function formName()
    {
        return '';
    }
}
