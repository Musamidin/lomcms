<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agents".
 *
 * @property integer $id
 * @property string $fio
 * @property string $phone
 * @property string $email
 * @property string $pid
 * @property integer $status
 * @property string $datetime
 */
class Agents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fio', 'phone', 'email', 'pid'], 'string'],
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
            'fio' => 'Fio',
            'phone' => 'Phone',
            'email' => 'Email',
            'pid' => 'Pid',
            'status' => 'Status',
            'datetime' => 'Datetime',
        ];
    }
}
