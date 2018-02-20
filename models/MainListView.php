<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "MainListView".
 *
 * @property string $fio
 * @property string $passport_id
 * @property string $phone
 * @property integer $ticket
 * @property string $golds
 * @property string $other_prod
 * @property string $description
 * @property string $loan
 * @property integer $currency
 * @property string $commission
 * @property double $percents
 * @property string $dateStart
 * @property string $dateEnd
 * @property integer $countDay
 * @property integer $status
 * @property string $sysDate
 */
class MainListView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MainListView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fio', 'passport_id', 'phone', 'golds', 'other_prod', 'description'], 'string'],
            [['ticket', 'loan', 'currency', 'commission', 'percents', 'dateStart', 'dateEnd', 'countDay', 'sysDate'], 'required'],
            [['ticket', 'currency', 'countDay', 'status'], 'integer'],
            [['loan', 'commission', 'percents'], 'number'],
            [['dateStart', 'dateEnd', 'sysDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'Fio',
            'passport_id' => 'Passport ID',
            'phone' => 'Phone',
            'ticket' => 'Ticket',
            'golds' => 'Golds',
            'other_prod' => 'Other Prod',
            'description' => 'Description',
            'loan' => 'Loan',
            'currency' => 'Currency',
            'commission' => 'Commission',
            'percents' => 'Percents',
            'dateStart' => 'Date Start',
            'dateEnd' => 'Date End',
            'countDay' => 'Count Day',
            'status' => 'Status',
            'sysDate' => 'Sys Date',
        ];
    }
}
