<?php

namespace app\models;

use Yii;


class MatchInfoModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MatchInfo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHome()
    {
        return $this->hasOne(Team::className(), ['TeamID' => 'HomeID']);
    }
}
