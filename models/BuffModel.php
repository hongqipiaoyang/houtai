<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sl_buff".
 *
 * @property integer $ID
 * @property integer $user_id
 * @property string $buff_title
 * @property string $buff_content
 * @property integer $browser_num
 * @property integer $comment_num
 * @property integer $like_num
 * @property integer $share_num
 * @property integer $last_comment_id
 * @property integer $is_top
 * @property integer $is_check
 * @property integer $add_time
 * @property string $thumb_img_file
 * @property string $img_file
 */
class buffModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sl_buff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'buff_content'], 'required'],
            [['user_id', 'browser_num', 'comment_num', 'like_num', 'share_num', 'last_comment_id', 'is_top', 'is_check', 'add_time'], 'integer'],
            [['buff_content'], 'string'],
            [['buff_title'], 'string', 'max' => 255],
            [['thumb_img_file', 'img_file'], 'string', 'max' => 1024]
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios['checkpass'] = ['is_check'];
        $scenarios['checktop'] = ['is_top'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_id' => 'User ID',
            'buff_title' => 'Buff Title',
            'buff_content' => 'Buff Content',
            'browser_num' => 'Browser Num',
            'comment_num' => 'Comment Num',
            'like_num' => 'Like Num',
            'share_num' => 'Share Num',
            'last_comment_id' => 'Last Comment ID',
            'is_top' => 'Is Top',
            'is_check' => 'Is Check',
            'add_time' => 'Add Time',
            'thumb_img_file' => 'Thumb Img File',
            'img_file' => 'Img File',
        ];
    }

    public function getbusiness_customer(){
        return $this->hasOne(UserModel::className(), ['id'=>'user_id']);
    }
}
