<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace app\models\cms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cms\Cate;

/**
 * CateSearch represents the model behind the search form about `app\models\cms\Cate`.
 */
class CateSearch extends Cate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parentid', 'orderid'], 'integer'],
            [['linkurl', 'seotitle', 'keywords', 'keyword', 'description', 'parentstr', 'catename', 'status', 'lang'], 'safe'],
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
    	//$sql = "select a.*, s.company as company, s.domain as domain, s.username as merchant from ".Admin::tableName()." as a left join ".Site::tableName()." as s on a.test_id = s.testid";
        //$query = Admin::findBySql($sql);
        //$query = Admin::find()->alias('a')->select(['a.*', 's.company as company', 's.domain as domain', 's.username as merchant'])->leftJoin(Site::tableName().' as s', ' a.test_id = s.testid');
        
        $query = Cate::find()->current();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'class' => Pagination::class,
                'defaultPageSize' => 300,
            ],
            'sort' => [
                //'class' => Sort::class,
                'defaultOrder' => [
                    'orderid' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'parentid' => $this->parentid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'catename', $this->catename]);
        
        //echo $dataProvider->query->createCommand()->rawSql;

        return $dataProvider;
    }
}