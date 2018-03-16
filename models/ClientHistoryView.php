<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ClientHistoryView".
 *
 * @property integer $id
 * @property string $fio
 * @property integer $ticket
 * @property string $golds
 * @property string $other_prod
 * @property string $loan
 * @property string $part_of_loan
 * @property integer $currency
 * @property double $percents
 * @property string $total_summ
 * @property string $actionDate
 * @property string $dateStart
 * @property integer $countDay
 * @property string $comission
 * @property string $description
 * @property string $username
 * @property integer $client_id
 * @property integer $user_id
 * @property integer $status
 */
class ClientHistoryView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ClientHistoryView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ticket', 'loan', 'currency', 'percents', 'actionDate', 'dateStart', 'countDay', 'comission', 'client_id', 'user_id'], 'required'],
            [['id', 'ticket', 'currency', 'countDay', 'client_id', 'user_id', 'status'], 'integer'],
            [['fio', 'golds', 'other_prod', 'description', 'username'], 'string'],
            [['loan', 'part_of_loan', 'percents', 'total_summ', 'comission'], 'number'],
            [['actionDate', 'dateStart'], 'safe'],
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
            'ticket' => 'Ticket',
            'golds' => 'Golds',
            'other_prod' => 'Other Prod',
            'loan' => 'Loan',
            'part_of_loan' => 'Part Of Loan',
            'currency' => 'Currency',
            'percents' => 'Percents',
            'total_summ' => 'Total Summ',
            'actionDate' => 'Action Date',
            'dateStart' => 'Date Start',
            'countDay' => 'Count Day',
            'comission' => 'Comission',
            'description' => 'Description',
            'username' => 'Username',
            'client_id' => 'Client ID',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }
}
