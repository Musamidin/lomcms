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
use app\models\Template;
use app\models\ReprintView;
use app\componets\HelperFunc;
use app\models\Recognition;
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
        $cData = Yii::$app->HelperFunc->midasCalc($do);
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
              $data = $command->queryAll();
              return json_encode(['status'=>0,'data'=>$data[0],'msg'=>'OK']);
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
        $calcData = Yii::$app->HelperFunc->midasCalc($do);
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
          //$data['page'] = 1;
          $data['shpcount'] = $this->psize;
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

        header('Content-Type: application/json');
        if($token == md5(Yii::$app->session->getId().'opn')){
          try{
          $query = ClientHistoryView::find()->where(['client_id'=> $cid]);
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
             'percent' => Yii::$app->HelperFunc->getSettData(2)],
             'msg'=>'OK']);
          }catch(Exception $e){
              return json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
          }
        }else{
          return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

}
