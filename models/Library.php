<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "library".
 *
 * @property integer $id
 * @property string $keyname
 * @property string $param
 * @property integer $status
 * @property string $datetime
 */
class Library extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyname'], 'required'],
            [['keyname', 'param'], 'string'],
            [['status'], 'integer'],
            [['datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyname' => 'Keyname',
            'param' => 'Param',
            'status' => 'Status',
            'datetime' => 'Datetime',
        ];
    }
}
