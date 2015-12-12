<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sl_que_init".
 *
 * @property integer $Id
 * @property integer $que_id
 * @property integer $user_id
 * @property integer $que_answer
 * @property integer $add_time
 * @property integer $money
 * @property integer $par_person
 * @property integer $f_par_persons
 * @property integer $lottery_state
 */
class QueInitModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sl_que_init';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['que_id', 'user_id', 'que_answer', 'add_time', 'money', 'par_person', 'f_par_persons', 'lottery_state'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'que_id' => 'Que ID',
            'user_id' => 'User ID',
            'que_answer' => 'Que Answer',
            'add_time' => 'Add Time',
            'money' => 'Money',
            'par_person' => 'Par Person',
            'f_par_persons' => 'F Par Persons',
            'lottery_state' => 'Lottery State',
        ];
    }
}
