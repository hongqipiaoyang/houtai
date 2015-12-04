<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sl_buff_reply".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property string $content
 * @property integer $p_user_id
 * @property integer $p_reply_id
 * @property integer $save_time
 */
class ReplyModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sl_buff_reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'comment_id', 'user_id', 'p_user_id', 'p_reply_id', 'save_time'], 'integer'],
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
            'comment_id' => 'Comment ID',
            'user_id' => 'User ID',
            'content' => 'Content',
            'p_user_id' => 'P User ID',
            'p_reply_id' => 'P Reply ID',
            'save_time' => 'Save Time',
        ];
    }


    public function getbusiness_customer(){
        return $this->hasOne(UserModel::className(), ['id'=>'user_id']);
    } 
}
