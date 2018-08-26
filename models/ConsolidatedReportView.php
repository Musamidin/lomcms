<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ConsolidatedReportView".
 *
 * @property string $user
 * @property string $fio
 * @property string $passport_id
 * @property string $date_of_issue
 * @property string $passport_issued
 * @property string $address
 * @property string $phone
 * @property integer $ticket
 * @property string $loan
 * @property double $percents
 * @property string $dateStart
 * @property string $dateEnd
 * @property integer $status
 * @property string $sysDate
 * @property string $actionDate
 * @property string $last_up_date
 */
class ConsolidatedReportView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ConsolidatedReportView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'fio', 'passport_id', 'date_of_issue', 'passport_issued', 'address', 'phone'], 'string'],
            [['ticket', 'loan', 'percents', 'dateStart', 'dateEnd', 'sysDate', 'actionDate', 'last_up_date'], 'required'],
            [['ticket', 'status'], 'integer'],
            [['loan', 'percents'], 'number'],
            [['dateStart', 'dateEnd', 'sysDate', 'actionDate', 'last_up_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user' => 'User',
            'fio' => 'Fio',
            'passport_id' => 'Passport ID',
            'date_of_issue' => 'Date Of Issue',
            'passport_issued' => 'Passport Issued',
            'address' => 'Address',
            'phone' => 'Phone',
            'ticket' => 'Ticket',
            'loan' => 'Loan',
            'percents' => 'Percents',
            'dateStart' => 'Date Start',
            'dateEnd' => 'Date End',
            'status' => 'Status',
            'sysDate' => 'Sys Date',
            'actionDate' => 'Action Date',
            'last_up_date' => 'Last Up Date',
        ];
    }
}
