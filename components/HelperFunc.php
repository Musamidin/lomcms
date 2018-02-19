<?php
namespace app\components;

use Yii;
use yii\base\Component;
/**
 *
 */
class HelperFunc extends Component
{
  public function dataProcessing($param)
  {
      $data = [];
      foreach ($param as $key => $value) {
          if($key == 'phone'){
            $data[$key] = json_encode($value);
          }elseif($key == 'gold'){
            $data[$key] = json_encode($value);
          }else{
            $data[$key] = $value;
          }
      }
      unset($data['token']);
    return $data;
  }
}


?>
