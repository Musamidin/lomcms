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
            [['keyname'], 'required','message' => 'Введите наименование!'],
            [['param'], 'required','message' => 'Введите значение!'],
            [['keyname'], 'string'],
            [
              ['param'],
              'number','numberPattern' => '/^\s*[-+]?[0-9]*[.]?[0-9]+([eE][-+]?[0-9]+)?\s*$/',
              'message' => 'Введите цифровое значение с плавающей точкой!'
            ],
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
