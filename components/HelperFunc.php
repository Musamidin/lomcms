<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\ReprintView;
use app\models\MainListView;
use yii\data\Pagination;
use app\models\Recognition;
use app\models\Library;
use app\models\Golds;
use app\models\MainList;
use app\models\RecView;
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

  public function midasCalc($do,$usingDay)
  {
    $retVal = [];
    $com = 0;
    $totSumm = 0;
    $minDays = 10;
    $minSumm = 100;
    $cdays = date_diff(date_create($do->dateStart),date_create())->days;
    if($cdays < $minDays){ $cdays = $minDays; }
    if($do->currency == 2){ //Если валюта USD
        if($do->status > 0){ //Если статус (был проден)
          $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
          $totSumm = (floatval($do->loan) + $com);
        }else{
          $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
          $totSumm = (floatval($do->loan) + $com);
        }
    }elseif($do->currency == 1){ //Если валюта KGS
        if($do->status > 0){ //Если статус (был проден)
            if(floatval($do->loan) > 1000){ //Если сумма ссуды > 1000
              $cdays = date_diff(date_create($do->dateStart),date_create())->days;
              $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
              $totSumm = (floatval($do->loan) + $com);
            }else{ //Если сумма ссуды < 1000
              $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
              if($cdays <= 30){
                $com = ($com < $minSumm) ? $minSumm : $com;
              }elseif($cdays <= 60){
                $com = ($com < ($minSumm * 2)) ? ($minSumm * 2) : $com;
              }elseif($cdays <= 90){
                $com = ($com < ($minSumm * 3)) ? ($minSumm * 3) : $com;
              }elseif($cdays <= 120){
                $com = ($com < ($minSumm * 4)) ? ($minSumm * 4) : $com;
              }
              $totSumm = (floatval($do->loan) + $com);
            }
        }else{ //Если статус 0 (Первый раз)
            if(floatval($do->loan) > 1000){ //Если сумма ссуды > 1000
              $ud = ($usingDay == 30) ? 30 : $cdays;
              $com = (floatval($do->loan) / 100 * floatval($do->percents) * $ud);
              if($com < $minSumm){ $com = $minSumm; }
              $totSumm = (floatval($do->loan) + $com);
            }else{ //Если сумма ссуды < 1000
              $com = (floatval($do->loan) / 100 * floatval($do->percents) * $cdays);
              if($cdays <= 30){
                $com = ($com < $minSumm) ? $minSumm : $com;
              }elseif($cdays <= 60){
                $com = ($com < ($minSumm * 2)) ? ($minSumm * 2) : $com;
              }elseif($cdays <= 90){
                $com = ($com < ($minSumm * 3)) ? ($minSumm * 3) : $com;
              }elseif($cdays <= 120){
                $com = ($com < ($minSumm * 4)) ? ($minSumm * 4) : $com;
              }
              $totSumm = (floatval($do->loan) + $com);
            }
        }
    }
    $retVal['comission'] = ceil($com);
    $retVal['totalsumm'] = ceil($totSumm);
    $retVal['countDay'] = $cdays;
    return $retVal;
  }

  public function getData($param)
  {
    $data = [];
    try{
      if($param['sts'] == 0){
          $data['count'] = MainListView::find()->filterWhere(['!=','status',-1])->count();
          $pagination = new Pagination(['defaultPageSize'=>$param['shpcount'],'totalCount'=> $data['count']]);
          $data['mlv'] = MainListView::find()
          ->filterWhere(['!=','status',-1])
          ->offset($pagination->offset)
          ->limit($pagination->limit)
          ->asArray()
          ->orderBy(['last_up_date'=>SORT_DESC])
          ->all();
      }else{
        $data['count'] = MainListView::find()->filterWhere(['status'=> $param['sts']])->count();
        $pagination = new Pagination(['defaultPageSize'=>$param['shpcount'],'totalCount'=> $data['count']]);
        $data['mlv'] = MainListView::find()
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->filterWhere(['status'=> $param['sts']])
        ->asArray()
        ->orderBy(['last_up_date'=>SORT_DESC])
        ->all();
      }
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
          if($param['datetime'] == 0){
            $data['count'] = RecView::find()->count();
            $pagination = new Pagination(['defaultPageSize'=>$param['shpcount'],'totalCount'=> $data['count']]);
            $data['rnlist'] = RecView::find()
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->orderBy(['date_system'=>SORT_DESC])
            ->all();
          }else{
            $query = RecView::find()->where(['BETWEEN','date_system',$param['datetime'].'T00:00:00',$param['datetime'].'T23:59:59']);
            $cQuery = clone $query;
            $data['count'] = $cQuery->count();
            $pagination = new Pagination(['defaultPageSize'=>$param['shpcount'],'totalCount'=> $data['count']]);
            $data['rnlist'] = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->orderBy(['date_system'=>SORT_DESC])
            ->all();
          }
          //$query = RecView::find()->where(['BETWEEN','actionDate',$datefrom,$dateto]);
          //$countQuery = clone $query;

          return $data;
    }catch(Exception $e){
        return $e->errorInfo;
      //echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
    }
  }

  public function getUserReport($param)
  {
    $resp = [];
    for($i=1; $i < 8; $i++){
       $command = Yii::$app->db->
       createCommand("SET NOCOUNT ON; EXEC dbo.DailyReport @type =:type,@dateFrom =:dateFrom,@dateTo =:dateTo,@curr =:curr");
       $command->bindValue(":type",$i);
       $command->bindValue(":dateFrom",$param['datefrom']);
       $command->bindValue(":dateTo",$param['dateto']);
       $command->bindValue(":curr",$param['curr']);
       $resp['rep'.$i] = $command->queryAll();
    }
    return $resp;
  }

  public function getTotalKassa($par)
  {
    $data = [];
    $data['strKgs'] = Yii::$app->db->createCommand("SELECT [dbo].[startAndEndKassa]('".$par['datefrom']."',1) AS KGS")->queryScalar();
    $data['strUsd'] = Yii::$app->db->createCommand("SELECT [dbo].[startAndEndKassa]('".$par['datefrom']."',2) AS USD")->queryScalar();

    $data['currKgs'] = Yii::$app->db->createCommand("SELECT [dbo].[currentKassa]('".$par['datefrom']."','".$par['dateto']."',1) AS totalKassa")->queryScalar();
    $data['currUsd'] = Yii::$app->db->createCommand("SELECT [dbo].[currentKassa]('".$par['datefrom']."','".$par['dateto']."',2) AS totalKassa")->queryScalar();

    return $data;
  }

  public function getStsBar()
  {
    $command = Yii::$app->db->
    createCommand("SET NOCOUNT ON; EXEC dbo.actionStatusbar");
    return $command->queryAll();

  }

  public function getSettData($status)
  {
      $query = Library::find();
      return $query->where(['status'=>$status])
             ->asArray()
             ->orderBy(['datetime'=>SORT_DESC])
             ->all();
  }

  public function getReport($datefrom,$dateto,$curr)
  {
    $resp = [];
    try{
      $resp['tickets'] = Yii::$app->db->createCommand("SELECT * FROM getTicketsCountAndSumm('{$datefrom}','{$dateto}')")->queryAll();
      $resp['curr_golds'] = Golds::find()->select('SUM(gramm) AS gramm,[sample]')
      ->where(['BETWEEN','actionDate',$datefrom,$dateto])
      ->andWhere('status IN(0,1,3,4)')->groupBy('[sample]')
      ->asArray()->all();
      $resp['strKgs'] = Yii::$app->db->createCommand("SELECT [dbo].[startAndEndKassa]('{$datefrom}',1) AS KGS")->queryScalar();
      $resp['strUsd'] = Yii::$app->db->createCommand("SELECT [dbo].[startAndEndKassa]('{$datefrom}',2) AS USD")->queryScalar();

      $resp['currKgs'] = Yii::$app->db->createCommand("SELECT [dbo].[currentKassa]('{$datefrom}','{$dateto}',1) AS totalKassa")->queryScalar();
      $resp['currUsd'] = Yii::$app->db->createCommand("SELECT [dbo].[currentKassa]('{$datefrom}','{$dateto}',2) AS totalKassa")->queryScalar();

      $resp['vydacha'] = Yii::$app->db->createCommand("SELECT * FROM mrLoan('{$datefrom}','{$dateto}',{$curr},0)")->queryAll();
      $resp['vykup'] = Yii::$app->db->createCommand("SELECT * FROM mrLoan('{$datefrom}','{$dateto}',{$curr},2)")->queryAll();
      $resp['comission_pog'] = Yii::$app->db->createCommand("SELECT * FROM mrComission('{$datefrom}','{$dateto}',{$curr},2)")->queryAll();
      $resp['comission_perez'] = Yii::$app->db->createCommand("SELECT * FROM mrComission('{$datefrom}','{$dateto}',{$curr},1)")->queryAll();
      $resp['ch_pog'] = Yii::$app->db->createCommand("SELECT * FROM mrPartLoan('{$datefrom}','{$dateto}',{$curr},1)")->queryAll();
      $resp['proch_prih'] = Yii::$app->db->createCommand("SELECT * FROM mrPrp('{$datefrom}','{$dateto}',{$curr},'Приход')")->queryAll();
      $resp['proch_rashod'] = Yii::$app->db->createCommand("SELECT * FROM mrPrp('{$datefrom}','{$dateto}',{$curr},'Расход')")->queryAll();

    }catch(Exception $e){
        return $e->errorInfo;
    }finally{
      return $resp;
    }
  }

  public function insertjsontoarr($item,$id)
  {
      try{
            $item['mid'] = $id;
            $item['status'] = 0;
            $item['actionDate'] = date('Y-m-d\TH:i:s');
            unset($item['num']);
            Yii::$app->db->createCommand()->insert('golds',$item)->execute();
      }catch(Exception $e){
          print_r($e->errorInfo);
      }
  }

  public function insertter($items) //for test
  {
      try{
        $do = (object)[];
        $do->{"dateStart"} = date('Y-m-d',strtotime($items['dateStart']));
        $do->{"status"} = $items['status'];
        $do->{"loan"} = $items['loan'];
        $do->{"percents"} = $items['percents'];
        $do->{"currency"} = 1;//$items['currency'];
        $cData = Yii::$app->HelperFunc->midasCalc($do,30);
        //print_r($do);
        $command = Yii::$app->db->
        createCommand("SET NOCOUNT ON; EXEC dbo.actionDataInport @loan =:loan,@currency =:currency,@percents =:percents,@comission =:comission, @totalsumm =:totalsumm, @description =:description,@other_prod =:other_prod,@gold =:gold,@user_id =:user_id,@ticket =:ticket,@cid =:cid,@dateStart=:dateStart");
        //$command->bindValue(":id",$items['id'])
        $command->bindValue(":loan",$items['loan'])
        ->bindValue(":currency",1)
        ->bindValue(":percents",$items['percents'])
        ->bindValue(":comission",$cData['comission'])
        ->bindValue(":totalsumm",$cData['totalsumm'])
        ->bindValue(":description",$items['thing_bail_description'])
        ->bindValue(":other_prod",$items['thing_bail'])
        ->bindValue(":gold",$items['golds'])
        ->bindValue(":user_id",2)
        ->bindValue(":ticket",$items['tickets_number'])
        ->bindValue(":cid",$items['clients_id'])
        ->bindValue(":dateStart",date('Y-m-d',strtotime($items['dateStart'])));

        // ->bindValue(":fio",$items['fio'])
        // ->bindValue(":date_of_issue",$items['date_of_issue'])
        // ->bindValue(":passport_id",$items['passport_id'])
        // ->bindValue(":passport_issued",$items['passport_issued'])
        // ->bindValue(":phone",$data['phone'])
        // ->bindValue(":address",$data['address']);
         $rtdata = $command->queryAll();
         print_r($rtdata);
        //$tarr = [];
        //$tarr2 = [];
          // $arr = json_decode($items['golds'],true);
          // foreach($arr as $itm){
          //   $itm['mid'] = $items['id'];
          //   $itm['status'] = $items['status'];
          //   unset($itm['num']);
          //   Yii::$app->db->createCommand()->insert('golds',$itm)->execute();
          //   print_r($itm['groups']);
          // }
          // foreach($arr['things'] as $item){
          //   //print_r($item);
          //     foreach($item as $k=>$v){
          //         if($k == 'bail_type'){
          //          $tarr['groups'] = $v;
          //         }elseif($k == 'thcurrency'){
          //           $tarr['currs'] = $v;
          //         }elseif($k == 'thcount'){
          //           $tarr['count'] = $v;
          //         }elseif($k == 'thgramm'){
          //           $tarr['gramm'] = $v;
          //         }elseif($k == 'rsumm'){
          //           $tarr['summ'] = $v;
          //         }elseif($k == 'sample'){
          //           $tarr['sample'] = $v;
          //         }
          //     }
          //   array_push($tarr2,$tarr);
          // }
          //print_r($items['codeid']);
          //update
          //Yii::$app->db2->createCommand("UPDATE sp_orders_tickets SET golds='".print_r(json_encode($tarr2),true)."' WHERE codeid =".$items['codeid']."")->execute();
          //
          //->update('sp_orders_tickets',['golds'=>json_encode($tarr2)],'codeid=:id',[':id'=>$item['codeid']]);
      }catch(Exception $e){
          print_r($e->errorInfo);
      }
  }

}
?>
