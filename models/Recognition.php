<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recognition".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date_system
 * @property string $status
 * @property string $transfer
 * @property string $comments
 * @property string $summ
 * @property integer $currency
 */
class Recognition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recognition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'required'],
            [['user_id', 'currency'], 'integer'],
            [['date_system'], 'safe'],
            [['status', 'transfer', 'comments'], 'string'],
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
            'date_system' => 'Date System',
            'status' => 'Status',
            'transfer' => 'Transfer',
            'comments' => 'Comments',
            'summ' => 'Summ',
            'currency' => 'Currency',
        ];
    }
}
