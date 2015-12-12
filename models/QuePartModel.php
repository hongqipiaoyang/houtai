<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sl_que_part".
 *
 * @property integer $id
 * @property integer $que_id
 * @property integer $init_id
 * @property integer $user_id
 * @property integer $add_time
 * @property integer $money
 * @property integer $init_option
 */
class QuePartModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sl_que_part';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['que_id', 'init_id', 'user_id', 'add_time', 'money', 'init_option'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'que_id' => 'Que ID',
            'init_id' => 'Init ID',
            'user_id' => 'User ID',
            'add_time' => 'Add Time',
            'money' => 'Money',
            'init_option' => 'Init Option',
        ];
    }
}
