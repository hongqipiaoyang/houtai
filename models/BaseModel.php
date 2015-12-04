<?php
namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;



class BaseModel extends ActiveRecord{
    
//    protected $table_name = null;

    public static function tableName() {
        $tableName = $this->table_name;
        return '{{%'.($tableName).'}}';
    }
}

?>