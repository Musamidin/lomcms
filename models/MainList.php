<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mainList".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $client_id
 * @property integer $ticket
 * @property string $golds
 * @property string $other_prod
 * @property string $description
 * @property string $loan
 * @property string $comission
 * @property double $percents
 * @property string $sysDate
 * @property string $actionDate
 * @property string $dateStart
 * @property string $dateEnd
 * @property integer $sms
 * @property integer $email
 * @property integer $status_sms
 * @property integer $status_email
 * @property integer $status
 * @property integer $astatus
 *
 * @property ActionList[] $actionLists
 */
class MainList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mainList';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'client_id', 'ticket', 'loan', 'comission', 'percents', 'sysDate', 'actionDate', 'dateStart', 'dateEnd'], 'required'],
            [['user_id', 'client_id', 'ticket', 'sms', 'email', 'status_sms', 'status_email', 'status', 'astatus'], 'integer'],
            [['golds', 'other_prod', 'description'], 'string'],
            [['loan', 'comission', 'percents'], 'number'],
            [['sysDate', 'actionDate', 'dateStart', 'dateEnd'], 'safe'],
            [['ticket'], 'unique'],
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
            'client_id' => 'Client ID',
            'ticket' => 'Ticket',
            'golds' => 'Golds',
            'other_prod' => 'Other Prod',
            'description' => 'Description',
            'loan' => 'Loan',
            'comission' => 'Comission',
            'percents' => 'Percents',
            'sysDate' => 'Sys Date',
            'actionDate' => 'Action Date',
            'dateStart' => 'Date Start',
            'dateEnd' => 'Date End',
            'sms' => 'Sms',
            'email' => 'Email',
            'status_sms' => 'Status Sms',
            'status_email' => 'Status Email',
            'status' => 'Status',
            'astatus' => 'aStatus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionLists()
    {
        return $this->hasMany(ActionList::className(), ['mid' => 'id']);
    }
}
