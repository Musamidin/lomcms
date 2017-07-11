<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mainListView".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $groupby
 * @property integer $insertion
 * @property integer $sample
 * @property integer $type_of_delivery
 * @property string $size
 * @property integer $count
 * @property double $weight_grams
 * @property string $price_buy
 * @property string $price_sale
 * @property string $price_sold
 * @property string $discount
 * @property integer $buy_currency
 * @property integer $sale_currency
 * @property string $date_of_arrival
 * @property string $date_of_sale
 * @property string $comment
 * @property integer $status
 * @property string $date_system
 * @property string $groupbyName
 * @property string $insertionName
 * @property string $sampleName
 * @property string $tdName
 */
class MainListView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mainListView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'groupby', 'insertion', 'sample', 'type_of_delivery', 'date_system'], 'required'],
            [['id', 'user_id', 'groupby', 'insertion', 'sample', 'type_of_delivery', 'count', 'buy_currency', 'sale_currency', 'status'], 'integer'],
            [['name', 'size', 'comment', 'groupbyName', 'insertionName', 'sampleName', 'tdName'], 'string'],
            [['weight_grams', 'price_buy', 'price_sale', 'price_sold', 'discount'], 'number'],
            [['date_of_arrival', 'date_of_sale', 'date_system'], 'safe'],
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
            'name' => 'Name',
            'groupby' => 'Groupby',
            'insertion' => 'Insertion',
            'sample' => 'Sample',
            'type_of_delivery' => 'Type Of Delivery',
            'size' => 'Size',
            'count' => 'Count',
            'weight_grams' => 'Weight Grams',
            'price_buy' => 'Price Buy',
            'price_sale' => 'Price Sale',
            'price_sold' => 'Price Sold',
            'discount' => 'Discount',
            'buy_currency' => 'Buy Currency',
            'sale_currency' => 'Sale Currency',
            'date_of_arrival' => 'Date Of Arrival',
            'date_of_sale' => 'Date Of Sale',
            'comment' => 'Comment',
            'status' => 'Status',
            'date_system' => 'Date System',
            'groupbyName' => 'Groupby Name',
            'insertionName' => 'Insertion Name',
            'sampleName' => 'Sample Name',
            'tdName' => 'Td Name',
        ];
    }
}
