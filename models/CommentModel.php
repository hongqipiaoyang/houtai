<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sl_buff_comment".
 *
 * @property integer $id
 * @property integer $buff_id
 * @property integer $user_id
 * @property string $content
 * @property integer $reply_num
 * @property integer $praice_num
 * @property integer $save_time
 * @property integer $last_reply_time
 */
class CommentModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sl_buff_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buff_id', 'user_id', 'reply_num', 'praice_num', 'save_time', 'last_reply_time'], 'integer'],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'buff_id' => 'Buff ID',
            'user_id' => 'User ID',
            'content' => 'Content',
            'reply_num' => 'Reply Num',
            'praice_num' => 'Praice Num',
            'save_time' => 'Save Time',
            'last_reply_time' => 'Last Reply Time',
        ];
    }

    public function getbusiness_customer(){
        return $this->hasOne(UserModel::className(), ['id'=>'user_id']);
    }
}
