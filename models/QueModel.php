<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sl_question".
 *
 * @property integer $Id
 * @property string $que_content
 * @property string $que_option
 * @property string $que_answer
 * @property integer $add_time
 * @property integer $start_time
 * @property integer $stop_time
 * @property integer $match_id
 * @property integer $zhi_id
 * @property integer $user_id
 * @property integer $check_state
 * @property integer $stop_state
 * @property integer $match_time
 * @property string $que_group
 * @property string $type
 * @property integer $time_option
 */
class QueModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sl_que_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_time', 'start_time','que_answer', 'stop_time', 'match_id', 'zhi_id', 'user_id', 'check_state', 'stop_state', 'match_time', 'time_option'], 'integer'],
            [['que_group', 'type'], 'string'],
            [['que_content'], 'string', 'max' => 255],
            [['que_option'], 'string', 'max' => 16]
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios['check'] = ['check_state'];
        $scenarios['remove'] = ['stop_state'];
        $scenarios['answer'] = ['que_answer'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'que_content' => 'Que Content',
            'que_option' => 'Que Option',
            'que_answer' => 'Que Answer',
            'add_time' => 'Add Time',
            'start_time' => 'Start Time',
            'stop_time' => 'Stop Time',
            'match_id' => 'Match ID',
            'zhi_id' => 'Zhi ID',
            'user_id' => 'User ID',
            'check_state' => 'Check State',
            'stop_state' => 'Stop State',
            'match_time' => 'Match Time',
            'que_group' => 'Que Group',
            'type' => 'Type',
            'time_option' => 'Time Option',
        ];
    }
    public function getzhibo(){
        return $this->hasOne(ZhiboModel::className(), ['id'=>'zhi_id']);
    }
}
