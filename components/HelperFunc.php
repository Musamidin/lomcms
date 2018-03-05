<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\ReprintView;
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

  public function midasCalc($do)
  {
    $retVal = [];
    $com = 0;
    $totSumm = 0;
    $minDays = 10;
    $minSumm = 100;
    $cdays = date_diff(date_create($do->dateStart),date_create())->days;
    if($do->currency == 2){ //Если валюта USD
        if($do->status > 0){ //Если статус (был проден)
          $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
          $totSumm = (floatval($do->loan) + $com);
        }else{
          if($cdays < $minDays){ $cdays = $minDays; }
          $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
          $totSumm = (floatval($do->loan) + $com);
        }
    }elseif($do->currency == 1){ //Если валюта KGS
        if($do->status > 0){ //Если статус (был проден)
            if(floatval($do->loan) > 1000){ //Если сумма ссуды > 1000
              $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
              $totSumm = (floatval($do->loan) + $com);
            }else{ //Если сумма ссуды < 1000
              if(($cdays % 30) !== 0){ $tempDays = (($cdays-($cdays % 30))/30+1); }
              $com = ($tempDays == 0 ? floatval($do->percents) : ($tempDays * floatval($do->percents)));
              $totSumm = (floatval($do->loan) + $com);
            }
        }else{ //Если статус 0 (Первый раз)
            if(floatval($do->loan) > 1000){ //Если сумма ссуды > 1000
              if($cdays < $minDays){ $cdays = $minDays; }
              $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
              if($com < $minSumm){ $com = $minSumm; }
              $totSumm = (floatval($do->loan) + $com);
            }else{ //Если сумма ссуды < 1000
              if(($cdays % 30) !== 0){ $tempDays = (($cdays-($cdays % 30))/30+1); }
              $com = ($tempDays == 0 ? floatval($do->percents) : ($tempDays * floatval($do->percents)));
              $totSumm = (floatval($do->loan) + $com);
            }
        }
    }
    $retVal['comission'] = $com;
    $retVal['totalsumm'] = round($totSumm);
    $retVal['countDay'] = $cdays;
    return $retVal;
  }



}
?>
