<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RecView".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $fio
 * @property string $date_system
 * @property string $status
 * @property string $transfer
 * @property string $comments
 * @property string $summ
 * @property integer $currency
 */
class RecView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RecView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'date_system', 'status', 'currency'], 'required'],
            [['id', 'user_id', 'currency'], 'integer'],
            [['fio', 'status', 'transfer', 'comments'], 'string'],
            [['date_system'], 'safe'],
            [['summ'], 'number'],
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
            'fio' => 'Fio',
            'date_system' => 'Date System',
            'status' => 'Status',
            'transfer' => 'Transfer',
            'comments' => 'Comments',
            'summ' => 'Summ',
            'currency' => 'Currency',
        ];
    }
}
