<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business_wallet".
 *
 * @property string $id
 * @property string $balance
 * @property string $freeze_money
 * @property string $history_prize
 * @property integer $status
 * @property string $summary
 * @property integer $version
 * @property string $amount
 * @property string $credit
 */
class WallertModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_wallet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance', 'freeze_money', 'history_prize', 'amount'], 'number'],
            [['status', 'version'], 'required'],
            [['status', 'version', 'credit'], 'integer'],
            [['summary'], 'string', 'max' => 255]
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios['jifen'] = ['credit'];


        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balance' => 'Balance',
            'freeze_money' => 'Freeze Money',
            'history_prize' => 'History Prize',
            'status' => 'Status',
            'summary' => 'Summary',
            'version' => 'Version',
            'amount' => 'Amount',
            'credit' => 'Credit',
        ];
    }
}
