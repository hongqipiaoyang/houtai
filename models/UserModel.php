<?php

namespace app\models;

use Yii;


class UserModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_win_money', 'ssuper_commission', 'ssuper_ratio', 'super_commission', 'super_ratio'], 'number'],
            [['bank', 'id_type', 'login_num', 'old', 'ploy_accur', 'ploy_consumed', 'usr_type', 'wallet_id', 'commission_id', 'ssuperior_id', 'superior_id', 'level', 'jifen', 'credent_type', 'is_auth'], 'integer'],
            [['sex', 'is_mobile_bind'], 'string'],
            [['email', 'nick_name', 'old', 'password', 'wallet_id'], 'required'],
            [['last_login_time', 'register_time'], 'safe'],
            [['bank_name', 'bank_number', 'city', 'id_no', 'customer_ip', 'email', 'mobile_no', 'nick_name', 'password', 'province', 'real_name', 'subbranch', 'user3_id', 'bound', 'question', 'yanzhenma', 'open_id', 'image', 'credent_no'], 'string', 'max' => 255],
            [['true_nick'], 'string', 'max' => 40],
            [['auth_tag'], 'string', 'max' => 100],
            [['nick_name'], 'unique'],
            [['wallet_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'all_win_money' => 'All Win Money',
            'bank' => 'Bank',
            'bank_name' => 'Bank Name',
            'bank_number' => 'Bank Number',
            'sex' => 'Sex',
            'city' => 'City',
            'id_no' => 'Id No',
            'id_type' => 'Id Type',
            'customer_ip' => 'Customer Ip',
            'email' => 'Email',
            'last_login_time' => 'Last Login Time',
            'login_num' => 'Login Num',
            'mobile_no' => 'Mobile No',
            'is_mobile_bind' => 'Is Mobile Bind',
            'nick_name' => 'Nick Name',
            'old' => 'Old',
            'password' => 'Password',
            'ploy_accur' => 'Ploy Accur',
            'ploy_consumed' => 'Ploy Consumed',
            'province' => 'Province',
            'real_name' => 'Real Name',
            'register_time' => 'Register Time',
            'subbranch' => 'Subbranch',
            'user3_id' => 'User3 ID',
            'usr_type' => 'Usr Type',
            'wallet_id' => 'Wallet ID',
            'ssuper_commission' => 'Ssuper Commission',
            'ssuper_ratio' => 'Ssuper Ratio',
            'super_commission' => 'Super Commission',
            'super_ratio' => 'Super Ratio',
            'commission_id' => 'Commission ID',
            'ssuperior_id' => 'Ssuperior ID',
            'superior_id' => 'Superior ID',
            'bound' => 'Bound',
            'question' => 'Question',
            'yanzhenma' => 'Yanzhenma',
            'open_id' => 'Open ID',
            'level' => 'Level',
            'jifen' => 'Jifen',
            'image' => 'Image',
            'credent_no' => 'Credent No',
            'credent_type' => 'Credent Type',
            'true_nick' => 'True Nick',
            'auth_tag' => 'Auth Tag',
            'is_auth' => 'Is Auth',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlJifenLogs()
    {
        return $this->hasMany(SlJifenLog::className(), ['customer_id' => 'id']);
    }
}
