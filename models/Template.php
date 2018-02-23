<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $temp
 * @property string $datetime
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['temp'], 'string'],
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
            'user_id' => 'User ID',
            'temp' => 'Temp',
            'datetime' => 'Datetime',
        ];
    }
}
