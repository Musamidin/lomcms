<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TypeOfDelivery".
 *
 * @property integer $id
 * @property string $name
 */
class TypeOfDelivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'typeOfDelivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}
