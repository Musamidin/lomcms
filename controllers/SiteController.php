<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\MainForm;
use app\models\ContactForm;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use app\models\Clients;
use app\models\MainList;
use app\models\MainListView;
use app\models\Library;
use app\models\User;
use app\models\Template;
use app\models\ReprintView;
use app\componets\HelperFunc;
use app\models\Recognition;
use app\models\Golds;
use app\models\ClientHistoryView;

class SiteController extends Controller
{
    public $psize = 15;

    public function beforeAction($action)
    {
        if (\Yii::$app->getUser()->isGuest && $action->id !== 'login' && $action->id !=='/'){
            Yii::$app->response->redirect(Url::to(['login']), 301);
            Yii::$app->end();
        }elseif($action->id === 'issuanceofcredit' || $action->id === 'getautocomplete'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'getdata' || $action->id === 'getprintpreviewdata'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'updatetemplate' || $action->id === 'gettemplate'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'calcaction' || $action->id ==='deleteaction'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'recognitionajax' || $action->id === 'getrecognitionajax'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'getuserreportajax' || $action->id === 'searchajax'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'gethistoryajax' || $action->id === 'getlibajax'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'setdataajax' || $action->id === 'realizeajax'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'getreportajax' || $action->id === 'exchangeajax'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'getsmsreportajax'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'deleteajax'){
          $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['getdata'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ( $model->load(Yii::$app->request->post()) && $model->login() ) {
            return $this->redirect('/');
        }else{
           $this->layout = 'loginLayout';
           return $this->render('login', ['model' => $model]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('login');
    }

      /* Render Controllers */
    public function actionIndex()
    {
      //$curr = [];
      try{
        // $xml = simplexml_load_string(file_get_contents("http://www.nbkr.kg/XML/daily.xml"));
        // $arr = (array)$xml;
        // $curr['date'] = $arr['@attributes']['Date'];
        // $currVal = (array)$arr['Currency'][0];
        // $curr['usd'] = number_format(str_replace(',','.',$currVal['Value'])+1,2);
        $clients = new Clients();
        $mainList = new MainList();
        $temp = Template::findOne(1);
        return $this->render('index',
                            ['clients' => $clients,
                             'mainList' => $mainList,
                             'temp' => $temp
                            ]);
      }catch(Exception $e){
        return $this->render('index',['error' => $e]);
      }

    }

    public function actionReport()
    {
      return $this->render('report');
    }

    public function actionUserreport()
    {
      return $this->render('userreport');
    }

    public function actionSmsreport()
    {
      return $this->render('smsreport');
    }

    public function actionRecognition()
    {
      return $this->render('recognition');
    }

    public function actionSettings()
    {
      if(Yii::$app->user->identity->role == 1){
        $lib = new Library();
        return $this->render('settings',['lib'=>$lib]);
      }else{
        return $this->redirect('/');
      }
    }

    /* Autocomplete AJAX Controllers */
    public function actionGetautocomplete()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        $term = $request->get('term');

        //header('Content-Type: application/json');
        if($token === md5(Yii::$app->session->getId().'opn')){
            try{
                $clients = Clients::find()->select(['id','fio','fio AS label','address','date_of_issue','passport_id','passport_issued','phone'])->where("fio LIKE '%".$term."%'")->asArray()->all();

                return json_encode($clients);
            }catch(Exception $e){
                return json_encode(array('status'=>2,'message'=>$e->errorInfo));
            }
        }else{
            return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionIssuanceofcredit()
    {
      $postData = file_get_contents("php://input");
      $do = json_decode($postData);
      $do->{"dateStart"} = date('Y-m-d');
      $do->{"status"} = 0;
      if(!empty($do->token) && $do->token == md5(Yii::$app->session->getId().'opn')){
        $data = Yii::$app->HelperFunc->dataProcessing($do);
        $cData = Yii::$app->HelperFunc->midasCalc($do,30);
        //print_r(Yii::$app->user->identity->id);
          try{
            $command = Yii::$app->db->
            createCommand("SET NOCOUNT ON; EXEC dbo.actionData @id =:id,@loan =:loan,@currency =:currency,@percents =:percents,@comission =:comission, @totalsumm =:totalsumm, @description =:description,@other_prod =:other_prod,@gold =:gold,@user_id =:user_id,@fio =:fio,@date_of_issue =:date_of_issue,@passport_id =:passport_id,@passport_issued =:passport_issued,@phone =:phone, @address =:address");
            $command->bindValue(":id",$data['id'])
            ->bindValue(":loan",$data['loan'])
            ->bindValue(":currency",$data['currency'])
            ->bindValue(":percents",$data['percents'])
            ->bindValue(":comission",$cData['comission'])
            ->bindValue(":totalsumm",$cData['totalsumm'])
            ->bindValue(":description",$data['description'])
            ->bindValue(":other_prod",$data['other_prod'])
            ->bindValue(":gold",$data['gold'])
            ->bindValue(":user_id",Yii::$app->user->identity->id)
            ->bindValue(":fio",$data['fio'])
            ->bindValue(":date_of_issue",$data['date_of_issue'])
            ->bindValue(":passport_id",$data['passport_id'])
            ->bindValue(":passport_issued",$data['passport_issued'])
            ->bindValue(":phone",$data['phone'])
            ->bindValue(":address",$data['address']);
             $rtdata = $command->queryAll();
              return json_encode(['status'=>0,'data'=>$rtdata[0],'msg'=>'OK']);
          }catch(Exception $e){
              //print_r($e->errorInfo[2]);
              echo json_encode(['status'=>1,'data'=>null,'msg'=>$e->errorInfo]);
          }
      }else{
           echo json_encode(['status'=>2,'data'=>null,'msg'=>'Ошибка! токен не соответствует']);
      }

    }

    public function actionGetprintpreviewdata()
    {
      $postData = file_get_contents("php://input");
      $do = json_decode($postData);
      header('Content-Type: application/json');
      if($do->token == md5(Yii::$app->session->getId().'opn'))
      {
          try{
            $rpv = ReprintView::find()->where("id = ".$do->id)->asArray()->all();
            return json_encode(['status'=>0,'data'=>$rpv[0],'msg'=>'OK']);
          }catch(Exception $e){
            return json_encode(['status'=>1, 'data'=> null,'msg'=>$e->errorInfo]);
          }
      }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
      }
    }

    /* MainList AJAX Controllers */
    public function actionGetdata()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        $data = [];
        $data['page'] = $request->get('page');
        $data['sts'] = $request->get('sts');
        $data['shpcount'] = $request->get('shpcount');
        header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
          $retData = Yii::$app->HelperFunc->getData($data);
          $stsbar = Yii::$app->HelperFunc->getStsBar();
          echo json_encode(['status'=>0,
                            'data'=>['stsbar'=>$stsbar[0],'mainlistview' => $retData['mlv'],'count' => $retData['count']],
                            'msg'=>'OK']
                          );
      }else{
        return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
      }
    }

    public function actionUpdatetemplate()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        header('Content-Type: application/json');
        if(Yii::$app->user->identity->role == 1){
            if($do->token === md5(Yii::$app->session->getId().'opn')){
                try{
                  $tp = Template::findOne(1);
                  $tp->user_id = Yii::$app->user->identity->id;
                  $tp->temp = $do->temp;
                  $tp->datetime = date('Y-m-d\TH:i:s');
                  $tp->save();
                  return json_encode(array('status'=>1,'message'=>'good!'));
                }catch(Exception $e){
                    return json_encode(array('status'=>2,'message'=>$e->errorInfo));
                }
                //print_r($tp);
            }else{
                return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
            }
       }else{
         return json_encode(array('status'=>4,'message'=>'Error("Права для этого пользователья ограничено!")'));
       }
    }

    public function actionGettemplate()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        header('Content-Type: application/json');
        if(Yii::$app->user->identity->role == 1){
            if($do->token === md5(Yii::$app->session->getId().'opn')){
                try{
                  $tp = Template::findOne(1);
                  return json_encode(array('status'=>1,'data'=>$tp->temp));
                }catch(Exception $e){
                  return json_encode(array('status'=>2,'message'=>$e->errorInfo));
                }
            }else{
                return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
            }
       }else{
         return json_encode(array('status'=>4,'message'=>'Error("Права для этого пользователья ограничено!")'));
       }
    }

    public function actionCalcaction()
    {
      $postData = file_get_contents("php://input");
      $do = json_decode($postData);
      header('Content-Type: application/json');
      /*
      Yii::$app->HelperFunc->midasCalc($do);
      Калькулятор для ломбарда Мидас
      Принимает параметр объект
      Возвращает массив ([comission] => 253.5 [totalsumm] => 15253.5 [countDay] => 13)
      */
      if($do->token === md5(Yii::$app->session->getId().'opn')){
        $calcData = Yii::$app->HelperFunc->midasCalc($do,0);
        $param['page'] = 1;
        $param['sts'] = 0;
        $param['shpcount'] = $this->psize;
        try{
          $command = Yii::$app->db->
          createCommand("SET NOCOUNT ON; EXEC dbo.actionCalc @id =:id, @comission =:comission, @totalSumm =:totalSumm, @countDay =:countDay, @part_of_loan =:part_of_loan, @status =:status");
          $command->bindValue(":id",$do->id)
          ->bindValue(":comission",$calcData['comission'])
          ->bindValue(":totalSumm",$calcData['totalsumm'])
          ->bindValue(":countDay",$calcData['countDay'])
          ->bindValue(":part_of_loan",$do->part_of_loan)
          ->bindValue(":status",$do->fstatus);
            $resp = $command->queryAll();
            if($resp[0]['status'] == 0){
                $retData = Yii::$app->HelperFunc->getData($param);
                $stsbar = Yii::$app->HelperFunc->getStsBar();
                echo json_encode(['status'=>0,
                                'data'=>['stsbar'=>$stsbar[0],'mainlistview' => $retData['mlv'],'count' => $retData['count']],
                                'msg'=>'OK']
                              );
            }else{
              //print_r($resp);
              echo json_encode(['status'=>1,'data'=>null,'msg'=>$resp->errorInfo]);
            }
        }catch(Exception $e){
            //print_r($e->errorInfo[2]);
            echo json_encode(['status'=>1,'data'=>null,'msg'=>$e->errorInfo]);
        }
      }else{
        return false;
      }
    }

    public function actionDeleteaction()
    {
          $data['page'] = 1;
          $data['shpcount'] = $this->psize;
          $data['sts'] = 0;
          $postData = file_get_contents("php://input");
          $do = json_decode($postData);
          header('Content-Type: application/json');
          if($do->token == md5(Yii::$app->session->getId().'opn')){
              $command = Yii::$app->db->
              createCommand("SET NOCOUNT ON; EXEC dbo.actionDelete @id =:id");
              $command->bindValue(":id",$do->id);
              $resp = $command->queryAll();
              if($resp[0]['status'] == 0){
                  $retData = Yii::$app->HelperFunc->getData($data);
                  echo json_encode(['status'=>0,
                                  'data'=>['mainlistview' => $retData['mlv'],'count' => $retData['count']],
                                  'msg'=>'OK']
                                );
              }else{
                //print_r($resp);
                echo json_encode(['status'=>1,'data'=>null,'msg'=>$resp->errorInfo]);
              }
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionGetrecognitionajax()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        $data['page'] = $request->get('page');
        $data['shpcount'] = $request->get('shpcount');
        $data['datetime'] = $request->get('datetime');
        header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
          $retData = Yii::$app->HelperFunc->getRecognition($data);
          echo json_encode(['status'=>0,
                            'data'=>['rnlist' => $retData['rnlist'],'count' => $retData['count']],
                            'msg'=>'OK']
                          );
      }else{
        return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
      }
    }

    public function actionRecognitionajax()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        header('Content-Type: application/json');
        if($do->token == md5(Yii::$app->session->getId().'opn')){
          $param['page'] = 1;
          $param['datetime'] = 0;
          $param['shpcount'] = $this->psize;
            try{
              $rn = new Recognition();
              $rn->user_id = Yii::$app->user->identity->id;
              $rn->date_system = date('Y-m-d\TH:i:s');
              $rn->status = $do->status;
              $rn->transfer = $do->transfer;
              $rn->comments = $do->comments;
              $rn->summ = $do->summ;
              $rn->currency = $do->currency;
              $rn->save();
              $rdata = Yii::$app->HelperFunc->getRecognition($param);
              echo json_encode(['status'=>0,
                              'data'=>['rnlist' => $rdata['rnlist'],'count' => $rdata['count']],
                              'msg'=>'OK']
                            );
            }catch(Exception $e){
                return json_encode(array('status'=>1,'message'=>$e->errorInfo));
            }
          }else{
            return json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
          }
    }

    public function actionGetuserreportajax()
    {
        $request = Yii::$app->request;
        //$retData = [];
        $token = $request->get('token');
        $data['datefrom'] = $request->get('datefrom');
        $data['dateto'] = $request->get('dateto');
        $data['typereport'] = $request->get('typereport');

        header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
            $data['curr'] = 1;
            $retKgs = Yii::$app->HelperFunc->getUserReport($data);
            $data['curr'] = 2;
            $retUsd = Yii::$app->HelperFunc->getUserReport($data);
            $kassa = Yii::$app->HelperFunc->getTotalKassa($data);
            //print_r($kassa);
            echo json_encode(['status'=>0,
                              'data'=>['kgs' => $retKgs,'usd' => $retUsd,'kassa'=>$kassa],
                              'msg'=>'OK']
                            );
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionSearchajax()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        header('Content-Type: application/json');
        //print_r($do);
        if($do->token == md5(Yii::$app->session->getId().'opn')){
          try{
                $mlv = MainListView::find()
                ->filterWhere(['LIKE', $do->field, $do->key])
                ->asArray()
                ->orderBy(['last_up_date'=>SORT_DESC])
                ->all();
                echo json_encode(['status'=>0,
                                'data'=>['mainlistview' => $mlv,'count' => 1],
                                'msg'=>'OK']
                              );
          }catch(Exception $e){
              echo json_encode(['status'=>1,'data'=>null,'msg'=>$resp->errorInfo]);
          }
        }else{

          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionGethistoryajax()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        $page = $request->get('page');
        $shpcount = $request->get('shpcount');
        $cid = $request->get('cid');
        $ticket = $request->get('ticket');

        header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
          try{
            if($ticket == 0){
              $wher = ['client_id'=> $cid];
            }elseif($cid == 0 && $ticket != 0){
              $wher = ['ticket'=> $ticket];
            }else{
              $wher = ['client_id'=> $cid,'ticket'=> $ticket];
            }
          //$wher = ($ticket == 0) ? ['client_id'=> $cid] : ['client_id'=> $cid,'ticket'=> $ticket];
          $query = ClientHistoryView::find()->where($wher)->andWhere('status NOT IN(-1)');
          $countQuery = clone $query;
          $pagination = new Pagination(['defaultPageSize'=>$shpcount,'totalCount'=> $countQuery->count()]);

          $cRating = $query->offset($pagination->offset)
          ->limit($pagination->limit)
          ->asArray()
          ->orderBy(['status'=>SORT_DESC])
          ->all();
            return json_encode(['status'=>0,
                              'data'=>['clientRating' => $cRating,'count' =>$countQuery->count()],
                              'msg'=>'OK']
                            );
          }catch(Exception $e){
              return json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
          }
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionGetlibajax()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
          try{
            return json_encode(['status'=>0,'data'=>
            ['article' => Yii::$app->HelperFunc->getSettData(0),
             'sample' => Yii::$app->HelperFunc->getSettData(1),
             'percent' => Yii::$app->HelperFunc->getSettData(2),
             'user' => User::find()->asArray()->all()
            ],
             'msg'=>'OK']);
          }catch(Exception $e){
              return json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
          }
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionSetdataajax()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData,true);
        $flag = 0;
        header('Content-Type: application/json');
        if($do['token'] == md5(Yii::$app->session->getId().'opn')){
          try{
            $lib = new Library();
            if($do['table'] == 'percent'){
                $lib->keyname = $do['keyname'];
                $lib->param = $do['param'];
                $lib->status = 2;
                $lib->datetime = date('Y-m-d\TH:i:s');
                $lib->save();
            }elseif($do['table'] == 'article'){
                $lib->keyname = $do['keyname'];
                $lib->param = 0;
                $lib->status = 0;
                $lib->datetime = date('Y-m-d\TH:i:s');
                $flag = ($lib->save()) ? 0 : $lib->errors;

            }elseif($do['table'] == 'sample'){
                $lib->keyname = $do['keyname'];
                $lib->param = 0;
                $lib->status = 1;
                $lib->datetime = date('Y-m-d\TH:i:s');
                $lib->save();
            }
            return json_encode(['status'=>$flag,'msg'=>$flag]);
          }catch(Exception $e){
              return json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
          }
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionRealizeajax()
    {
          $postData = file_get_contents("php://input");
          $do = json_decode($postData);
          header('Content-Type: application/json');
          if($do->token == md5(Yii::$app->session->getId().'opn')){
              $command = Yii::$app->db->
              createCommand("SET NOCOUNT ON; EXEC dbo.actionRealize @id =:id, @status =:status");
              $command->bindValue(":id",$do->id);
              $command->bindValue(":status",$do->status);
              $resp = $command->queryAll();
              if($resp[0]['status'] == 0){
                  echo json_encode(['status'=>$resp[0]['status'],'msg'=>$resp[0]['ErrorMessage']]);
              }else{
                echo json_encode(['status'=>1,'data'=>null,'msg'=>$resp->errorInfo]);
              }
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionGetreportajax()
    {
      $request = Yii::$app->request;
      $token = $request->get('token');
      $datefrom = $request->get('datefrom');
      $dateto = $request->get('dateto');
          header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
              $resp = Yii::$app->HelperFunc->getReport($datefrom,$dateto,1);
              //print_r(Yii::$app->db->createCommand("SELECT [dbo].[startAndEndKassa]('{$datefrom}',1) AS KGS")->queryScalar());
              echo json_encode(['status'=>0,'data'=>$resp]);
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionExchangeajax()
    {
          $postData = file_get_contents("php://input");
          $do = json_decode($postData);
          header('Content-Type: application/json');
          if($do->token == md5(Yii::$app->session->getId().'opn')){
            $rec = new Recognition();
            $curr = ($do->getExchange == 1) ? 2 : 1;
            //print_r($do);
            $rec->user_id = Yii::$app->user->identity->id;
            $rec->date_system = date('Y-m-d\TH:i:s');
            $rec->status = 'Приход';
            $rec->comments = 'Конвертация по курсу '.floatval($do->exch_curr).'';
            $rec->summ = $do->exch_summ;
            $rec->currency = $curr;
            $rec->save();
            $recr = new Recognition();
            $recr->user_id = Yii::$app->user->identity->id;
            $recr->date_system = date('Y-m-d\TH:i:s');
            $recr->status = 'Расход';
            $recr->comments = 'Конвертация по курсу '.floatval($do->exch_curr).'';
            $recr->summ = $do->exchangedSumm;
            $recr->currency = $do->getExchange;
            $recr->save();
              // if($rec->save()){
                   echo json_encode(['status'=>0,'msg'=>'OK']);
              // }else{
                //echo json_encode(['status'=>1,'msg'=>'some ERROR!']);
              //}
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionDeleteajax()
    {
          $postData = file_get_contents("php://input");
          $do = json_decode($postData);
          $data = [];
          $data['page'] = 1;
          $data['shpcount'] = 15;
          $data['datetime'] = 0;
          header('Content-Type: application/json');
          if($do->token == md5(Yii::$app->session->getId().'opn')){
            $rec = Recognition::findOne($do->id);
            $rec->delete();
            $retData = Yii::$app->HelperFunc->getRecognition($data);
            echo json_encode(['status'=>0,
                                'data'=>['rnlist' => $retData['rnlist'],'count' => $retData['count']],
                                'msg'=>'OK']
                              );

            //print_r($recData);
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionGetsmsreportajax()
    {
      $request = Yii::$app->request;
      $token = $request->get('token');
      header('Content-Type: application/json');
      if($token == md5(Yii::$app->session->getId().'opn')){
        $param['page'] = $request->get('page');
        $param['datetime'] = $request->get('datetime');
        $param['shpcount'] = $request->get('shpcount');
          try{
            $rdata = Yii::$app->HelperFunc->getSMSReport($param);
            echo json_encode(['status'=>0,
                            'data'=>['smslist' => $rdata['smslist'],'count' => $rdata['count']],
                            'msg'=>'OK']
                          );
          }catch(Exception $e){
              return json_encode(array('status'=>1,'message'=>$e->errorInfo));
          }
        }else{
           return json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
        }

    }

    public function actionTest()
    {
        try{
          //$resp = Yii::$app->db->createCommand("SELECT * FROM [dbo].[mainList] WHERE golds IS NOT NULL")->queryAll(); //status IN(0,1,3,4) AND codeid = 7 AND currency = 'KGS'
          //$resp = Yii::$app->db2->createCommand("SELECT * FROM [dbo].[sp_orders_tickets] WHERE ns = 4 AND currency='USD'")->queryAll();
          //foreach($resp as $item){
          //  Yii::$app->HelperFunc->insertter($item);
            //print_r($item);
          //}
          //
          echo '<pre>';
          echo 'test';//print_r($resp);
          echo '</pre>';
        }catch(Exception $e){
            print_r( $e->errorInfo);
        }
    }
}
