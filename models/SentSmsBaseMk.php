<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sentSmsBaseMk".
 *
 * @property integer $id
 * @property string $phone
 * @property string $tbid
 * @property string $msgText
 * @property integer $smsCount
 * @property integer $status
 * @property string $datetime

 */
class SentSmsBaseMk extends \yii\db\ActiveRecord
{
    //Or db2
    public static function getDb() {
        return Yii::$app->db2;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sentSmsBaseMk';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'phone',
            'tbid' => 'tbid',
            'msgText' => 'msgText',
            'smsCount' => 'smsCount',
            'status' => 'status',
            'datetime' => 'datetime',
        ];
    }
}
