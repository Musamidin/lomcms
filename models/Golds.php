<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "golds".
 *
 * @property integer $id
 * @property integer $mid
 * @property string $groups
 * @property string $sample
 * @property integer $count
 * @property string $gramm
 * @property integer $status
 * @property string $summ
 * @property string $currs
 * @property string $datetime
 * @property string $actionDate
 */
class Golds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'golds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mid', 'status'], 'required'],
            [['mid', 'count', 'status'], 'integer'],
            [['groups', 'sample', 'currs'], 'string'],
            [['gramm', 'summ'], 'number'],
            [['datetime', 'actionDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mid' => 'Mid',
            'groups' => 'Groups',
            'sample' => 'Sample',
            'count' => 'Count',
            'gramm' => 'Gramm',
            'status' => 'Status',
            'summ' => 'Summ',
            'currs' => 'Currs',
            'datetime' => 'Datetime',
            'actionDate' => 'Action Date',
        ];
    }
}
