<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\ReprintView;
use app\models\MainListView;
use yii\data\Pagination;
use app\models\Recognition;
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

  public function getData($param)
  {
    $data = [];
    try{
          $data['count'] = MainListView::find()->count();
          $pagination = new Pagination(['defaultPageSize'=>$param['shpcount'],'totalCount'=> $data['count']]);
          $data['mlv'] = MainListView::find()
          ->offset($pagination->offset)
          ->limit($pagination->limit)
          ->asArray()
          ->orderBy(['last_up_date'=>SORT_DESC])
          ->all();
          return $data;
    }catch(Exception $e){
        return $e->errorInfo;
      //echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
    }
  }
  public function getRecognition($param)
  {
    $data = [];
    try{
          $data['count'] = Recognition::find()->count();
          $pagination = new Pagination(['defaultPageSize'=>$param['shpcount'],'totalCount'=> $data['count']]);
          $data['rnlist'] = Recognition::find()
          ->offset($pagination->offset)
          ->limit($pagination->limit)
          ->asArray()
          ->orderBy(['date_system'=>SORT_DESC])
          ->all();
          return $data;
    }catch(Exception $e){
        return $e->errorInfo;
      //echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
    }
  }

}
?>
