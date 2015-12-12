<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "BaskMatchInfo".
 *
 * @property integer $MatchID
 * @property string $FixtureID
 * @property string $GroupID
 * @property string $HomeID
 * @property string $AwayID
 * @property integer $HomeOne
 * @property integer $AwayOne
 * @property integer $HomeTwo
 * @property integer $AwayTwo
 * @property integer $HomeThree
 * @property integer $AwayThree
 * @property integer $HomeFour
 * @property integer $AwayFour
 * @property string $HomeAddGoals
 * @property string $AwayAddGoals
 * @property integer $AddTimeCount
 * @property integer $HomeGoals
 * @property integer $AwayGoals
 * @property integer $MatchState
 * @property string $CostTime
 * @property string $isNeutral
 * @property integer $AddTime
 * @property integer $LastTime
 * @property string $MatchDateTime
 * @property integer $SclassType
 *
 * @property BaskTeam $away
 * @property BaskTeam $home
 * @property BaskFixture $fixture
 */
class BaskMatchInfoModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BaskMatchInfo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FixtureID', 'GroupID', 'HomeID', 'AwayID', 'HomeOne', 'AwayOne', 'HomeTwo', 'AwayTwo', 'HomeThree', 'AwayThree', 'HomeFour', 'AwayFour', 'AddTimeCount', 'HomeGoals', 'AwayGoals', 'MatchState', 'AddTime', 'LastTime', 'SclassType'], 'integer'],
            [['isNeutral'], 'string'],
            [['MatchDateTime'], 'required'],
            [['MatchDateTime'], 'safe'],
            [['HomeAddGoals', 'AwayAddGoals'], 'string', 'max' => 100],
            [['CostTime'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MatchID' => 'Match ID',
            'FixtureID' => 'Fixture ID',
            'GroupID' => 'Group ID',
            'HomeID' => 'Home ID',
            'AwayID' => 'Away ID',
            'HomeOne' => 'Home One',
            'AwayOne' => 'Away One',
            'HomeTwo' => 'Home Two',
            'AwayTwo' => 'Away Two',
            'HomeThree' => 'Home Three',
            'AwayThree' => 'Away Three',
            'HomeFour' => 'Home Four',
            'AwayFour' => 'Away Four',
            'HomeAddGoals' => 'Home Add Goals',
            'AwayAddGoals' => 'Away Add Goals',
            'AddTimeCount' => 'Add Time Count',
            'HomeGoals' => 'Home Goals',
            'AwayGoals' => 'Away Goals',
            'MatchState' => 'Match State',
            'CostTime' => 'Cost Time',
            'isNeutral' => 'Is Neutral',
            'AddTime' => 'Add Time',
            'LastTime' => 'Last Time',
            'MatchDateTime' => 'Match Date Time',
            'SclassType' => 'Sclass Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAway()
    {
        return $this->hasOne(BaskTeam::className(), ['TeamID' => 'AwayID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHome()
    {
        return $this->hasOne(BaskTeam::className(), ['TeamID' => 'HomeID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixture()
    {
        return $this->hasOne(BaskFixture::className(), ['FixtureID' => 'FixtureID']);
    }
}
