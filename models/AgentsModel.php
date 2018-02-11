<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "agents".
 *
 * @property integer $id
 * @property string $fio
 * @property string $phone
 * @property string $email
 * @property string $pid
 * @property integer $status
 * @property string $datetime
 */
class AgentsModel extends Model
{
  public $id;
  public $fio;
  public $phone;
  public $email;
  public $pid;
  public $status;
  public $datetime;

    /**
     * @inheritdoc
     */
/*    public static function tableName()
    {
        return 'agents';
    }
*/
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['fio','required','message' => 'Введите Ф.И.О.!'],
            ['email','required','message' => 'Введите email адрес!'],
            ['phone','required','message' => 'Введите мобильный номер в 9 значном формате!'],
            ['pid','required','message' => 'Введите Паспортные данные!'],
            [['fio', 'phone', 'email', 'pid'], 'string'],
            [['status'], 'integer'],
            [['datetime'], 'safe'],
            [['phone'], 'match', 'pattern' => '/^[0-9]{9}$/','message' => 'Введите номер в 9 значном формате!'],
            ['email','email','message' => 'Не правельный e-mail адрес!'],
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
            'phone' => 'Phone',
            'email' => 'Email',
            'pid' => 'Pid',
            'status' => 'Status',
            'datetime' => 'Datetime',
        ];
    }
}
