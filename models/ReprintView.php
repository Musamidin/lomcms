<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ReprintView".
 *
 * @property integer $id
 * @property integer $ticket
 * @property string $golds
 * @property string $other_prod
 * @property string $description
 * @property string $loan
 * @property integer $currency
 * @property string $comission
 * @property double $percents
 * @property string $dateStart
 * @property string $dateEnd
 * @property string $fio
 * @property string $passport_id
 * @property string $date_of_issue
 * @property string $passport_issued
 * @property string $address
 * @property string $phone
 */
class ReprintView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ReprintView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ticket', 'loan', 'currency', 'comission', 'percents', 'dateStart', 'dateEnd'], 'required'],
            [['id', 'ticket', 'currency'], 'integer'],
            [['golds', 'other_prod', 'description', 'fio', 'passport_id', 'passport_issued', 'address', 'phone'], 'string'],
            [['loan', 'comission', 'percents'], 'number'],
            [['dateStart', 'dateEnd', 'date_of_issue'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket' => 'Ticket',
            'golds' => 'Golds',
            'other_prod' => 'Other Prod',
            'description' => 'Description',
            'loan' => 'Loan',
            'currency' => 'Currency',
            'comission' => 'Comission',
            'percents' => 'Percents',
            'dateStart' => 'Date Start',
            'dateEnd' => 'Date End',
            'fio' => 'Fio',
            'passport_id' => 'Passport ID',
            'date_of_issue' => 'Date Of Issue',
            'passport_issued' => 'Passport Issued',
            'address' => 'Address',
            'phone' => 'Phone',
        ];
    }
}
