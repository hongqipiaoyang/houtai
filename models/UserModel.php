<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business_customer".
 *
 * @property string $id
 * @property string $all_win_money
 * @property integer $bank
 * @property string $bank_name
 * @property string $bank_number
 * @property string $sex
 * @property string $city
 * @property string $id_no
 * @property integer $id_type
 * @property string $customer_ip
 * @property string $email
 * @property string $last_login_time
 * @property integer $login_num
 * @property string $mobile_no
 * @property string $is_mobile_bind
 * @property string $nick_name
 * @property integer $old
 * @property string $password
 * @property string $ploy_accur
 * @property string $ploy_consumed
 * @property string $province
 * @property string $real_name
 * @property string $register_time
 * @property string $subbranch
 * @property string $user3_id
 * @property integer $usr_type
 * @property string $wallet_id
 * @property string $ssuper_commission
 * @property string $ssuper_ratio
 * @property string $super_commission
 * @property string $super_ratio
 * @property string $commission_id
 * @property string $ssuperior_id
 * @property string $superior_id
 * @property string $bound
 * @property string $question
 * @property string $yanzhenma
 * @property string $open_id
 * @property integer $level
 * @property string $jifen
 * @property string $image
 * @property string $credent_no
 * @property integer $credent_type
 * @property string $true_nick
 * @property string $auth_tag
 * @property integer $is_auth
 *
 * @property SlJifenLog[] $slJifenLogs
 */
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
