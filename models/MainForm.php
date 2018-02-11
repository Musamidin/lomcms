<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class MainForm extends Model
{
    public $from_agent_id;
    public $name;
    public $size;
    public $exchangerate;
    public $weight_grams;
    public $insertion;
    public $sample;
    public $type_of_delivery;
    public $groupby;
    public $price_buy;
    public $buy_currency;
    public $price_sale;
    public $price_sold;
    public $sale_currency;
    public $comment;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
                [
                    ['size','price_buy','price_sale','price_sold'],
                    'integer','message' => 'Введите цифровое значение!'
                ],
                [
                  ['weight_grams','exchangerate'],
                  'number',
                  'numberPattern' => '/^\s*[-+]?[0-9]*[.]?[0-9]+([eE][-+]?[0-9]+)?\s*$/',
                  'message' => 'Введите цифровое значение!'
                ],
                ['name','required','message' => 'Введите название!'],
                ['size','required','message' => 'Введите размер!'],
                ['exchangerate','required','message' => 'Введите количество!'],
                ['weight_grams','required','message' => 'Введите вес,гр.!'],
                ['insertion','required','message' => 'Выберите вставку!'],
                ['sample','required','message' => 'Выберите пробу!'],
                ['type_of_delivery','required','message' => 'Выберите поставщика!'],
                ['groupby','required','message' => 'Выберите группу!'],
                ['price_buy','required','message' => 'Введите цену покупки!'],
                ['buy_currency','required','message' => 'Выберите валюту покупки!'],
                ['price_sale','required','message' => 'Введите цену продажа!'],
                ['price_sold','required','message' => 'Введите цену продажа!'],
                ['sale_currency','required','message' => 'Выберите валюту продажа!']
            // email has to be a valid email address
            //['email', 'email'],
            // verifyCode needs to be entered correctly
            //['verifyCode', 'captcha'],

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
