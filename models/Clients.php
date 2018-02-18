<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property integer $id
 * @property string $fio
 * @property string $passport_id
 * @property string $date_of_issue
 * @property string $passport_issued
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property integer $user_id
 * @property string $datetime
 * @property string $last_up_date
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['fio'], 'required'],
            [['fio', 'passport_id', 'passport_issued', 'address', 'email'], 'string'],
            [['date_of_issue', 'datetime','last_up_date'], 'safe'],
            //[['phone'], 'number'],
            [['user_id'], 'integer'],
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
            'passport_id' => 'Passport ID',
            'date_of_issue' => 'Date Of Issue',
            'passport_issued' => 'Passport Issued',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'user_id' => 'User ID',
            'datetime' => 'Datetime',
            'last_up_date'=> 'Update Date'
        ];
    }
}
