<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\MainForm;
use app\models\ContactForm;
use app\models\Consumption;
use yii\data\Pagination;
use yii\helpers\Url;
use app\models\MainList;
use app\models\MainListView;
use app\models\Agents;
use app\models\AgentsModel;
use app\models\Sample;
use app\models\Insertion;
use app\models\TypeOfDelivery;
use app\models\ProductGroup;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
//use app\componets\Init;

class SiteController extends Controller
{

    public function beforeAction($action)
    {
        if (\Yii::$app->getUser()->isGuest && $action->id !== 'login' && $action->id !=='/'){
            Yii::$app->response->redirect(Url::to(['login']), 301);
            Yii::$app->end();
        }elseif($action->id === 'setdata' || $action->id === 'setlib'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'getdata'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'deleterow'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'search' || $action->id === 'searchagent'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'allactions'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'getagentdata' || $action->id === 'getlib'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'getreport'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'setagent' || $action->id === 'deleteagent'){
          $this->enableCsrfValidation = false;
        }elseif($action->id === 'test'){
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

    public function actionAbout()
    {
        return $this->render('about');
    }
    /* Index Controllers */
    public function actionIndex()
    {
      $curr = [];
      try{
        $xml = simplexml_load_string(file_get_contents("http://www.nbkr.kg/XML/daily.xml"));
        $arr = (array)$xml;
        $curr['date'] = $arr['@attributes']['Date'];
        $currVal = (array)$arr['Currency'][0];
        $curr['usd'] = number_format(str_replace(',','.',$currVal['Value'])+1,2);
        $mainForm = new MainForm();
        $mainlist = MainListView::find()->all();
        $agentsList = Agents::find()->select(['fio','id'])->asArray()->where('status = 1')->all();
        $agentsModel = new AgentsModel();
        $agList = [];
        foreach ($agentsList as $item) {
            $agList[$item['id']] = $item['fio'];
        }
        return $this->render('index',
                            ['mainlist' => $mainlist,
                             'mainForm' => $mainForm,
                             'agentsModel' => $agentsModel,
                             'agentsList' => $agList,
                             'curr' => $curr
                            ]);
      }catch(Exception $e){
        return $this->render('index',['error' => $e]);
      }
    }
    /* Agents Controllers */
    public function actionAgents()
    {
        $agentsList = Agents::find()->select(['fio','id'])->asArray()->where('status = 1')->all();
        $agentsModel = new AgentsModel();
        return $this->render('agents',['agentModel'=>$agentsModel, 'agentList' => $agentsList]);
    }
    /* Agents AJAX Controllers */
    public function actionSetagent()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        header('Content-Type: application/json');
        if($do->token === md5(Yii::$app->session->getId().'opn')){
            try{
              $al = empty($do->dataid) ? new Agents() : Agents::findOne($do->dataid);
              $al->fio = $do->fio;
              $al->email = $do->email;
              $al->phone = $do->phone;
              $al->pid = $do->pid;
              $al->save();
              return json_encode(array('status'=>1,'message'=>'good!'));
            }catch(Exception $e){
                return json_encode(array('status'=>2,'message'=>$e->errorInfo));
            }
        }else{
            return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionDeleteagent()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        header('Content-Type: application/json');
        if($do->token === md5(Yii::$app->session->getId().'opn')){
            try{
              $al = Agents::findOne($do->id);
              $al->status = 0;
              $al->save();
              return json_encode(array('status'=>1,'message'=>'good!'));
            }catch(Exception $e){
                return json_encode(array('status'=>2,'message'=>$e->errorInfo));
            }
        }else{
            return json_encode(array('status'=>3,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionGetagentdata()
    {
      $request = Yii::$app->request;
      $page = $request->get('page');
      $shpcount = $request->get('shpcount');
      header('Content-Type: application/json');
      try{
          $count = Agents::find()->count();
          $pagination = new Pagination(['defaultPageSize'=>$shpcount,'totalCount'=> $count]);
          $al = Agents::find()
                      ->where('status = 1')
                      ->offset($pagination->offset)
                      ->limit($pagination->limit)
                      ->asArray()
                      ->orderBy(['datetime'=>SORT_DESC])
                      ->all();
          echo json_encode(['status'=>0,'data'=>
                                                ['agentsList' => $al,
                                                 'count' => $count,
                                                 'msg'=>'OK']
                                              ]);
      }catch(Exception $e){
          echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
      }
    }

    public function actionSearchagent()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);

        header('Content-Type: application/json');
        //if($do->token === md5(Yii::$app->session->getId().'opn')){
            if(!empty($do->key)){
                $al = Agents::find()
                    ->filterWhere(['LIKE', 'fio', $do->key])
                    ->asArray()
                    ->orderBy(['datetime'=>SORT_DESC])
                    ->all();
            }else{
                $al = Agents::find()
                ->asArray()
                ->where('status = 1')
                ->orderBy(['datetime'=>SORT_DESC])
                ->all();
            }
            echo json_encode($al);
        // }else{
        //     echo json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
        // }
        //echo $do->stype;
    }
    /* Report Controllers */
    public function actionReport()
    {
        $model = new ContactForm();
        // if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
        //     Yii::$app->session->setFlash('contactFormSubmitted');
        //
        //     return $this->refresh();
        // }
        return $this->render('report', [
            'model' => $model,
        ]);
    }
    /* Report AJAX Controllers */
    public function actionGetreport()
    {
      $request = Yii::$app->request;
      $dateFrom = $request->get('datefrom');
      $dateTo = $request->get('dateto');
      header('Content-Type: application/json');
      try{
        $sql1 = "SELECT *,(price_sold - (ISNULL(price_buy,0) * exchangerate)) AS dohod FROM actionReport(2,1) WHERE date_system BETWEEN CONVERT(datetime,'".$dateFrom." 00:00:00',102) AND CONVERT(datetime,'".$dateTo." 23:59:59',102)";
        $sql2 = "SELECT *,(price_sold - ISNULL(price_buy,0)) AS dohod FROM actionReport(1,1) WHERE date_system BETWEEN CONVERT(datetime,'".$dateFrom." 00:00:00',102) AND CONVERT(datetime,'".$dateTo." 23:59:59',102)";
        $sql3 = "SELECT *,(price_sold - ISNULL(price_buy,0)) AS dohod FROM actionReport(1,2) WHERE date_system BETWEEN CONVERT(datetime,'".$dateFrom." 00:00:00',102) AND CONVERT(datetime,'".$dateTo." 23:59:59',102)";
        $report1 = Yii::$app->db->createCommand($sql1)->queryAll();
        $report2 = Yii::$app->db->createCommand($sql2)->queryAll();
        $report3 = Yii::$app->db->createCommand($sql3)->queryAll();
          echo json_encode(['status'=>0,'data'=> ['report1' =>$report1,'report2' =>$report2,'report3' =>$report3],'msg'=>'OK']);
      }catch(Exception $e){
          echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
      }
    }
    /* MainList AJAX Controllers */
    public function actionGetdata()
    {
        $request = Yii::$app->request;
        $page = $request->get('page');
        $shpcount = $request->get('shpcount');
        header('Content-Type: application/json');
        try{
              $sample = Sample::find()->asArray()->all();
              $insertion = Insertion::find()->asArray()->all();
              $productGroup = ProductGroup::find()->asArray()->all();
              $typeOfDelivery = TypeOfDelivery::find()->asArray()->all();
              $count = MainListView::find()->count();
              $pagination = new Pagination(['defaultPageSize'=>$shpcount,'totalCount'=> $count]);
              $ml = MainListView::find()
              ->offset($pagination->offset)
              ->limit($pagination->limit)
              ->asArray()
              ->orderBy(['date_system'=>SORT_DESC])
              ->all();
              echo json_encode(['status'=>0,
                                'data'=>['mainlist' => $ml,
                                         'count' => $count,
                                         'sample' => $sample,
                                         'insertion' => $insertion,
                                         'productGroup' => $productGroup,
                                         'typeOfDelivery' => $typeOfDelivery],
                                'msg'=>'OK']
                              );
        }catch(Exception $e){
          echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
        }

    }

    public function actionSearch()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);

        header('Content-Type: application/json');

        if($do->token === md5(Yii::$app->session->getId().'opn')){
            if(!empty($do->key)){
                $ml = MainListView::find()
                    ->filterWhere(['LIKE', 'name', $do->key])
                    ->asArray()
                    ->orderBy(['date_system'=>SORT_DESC])
                    ->all();
            }else{
                $ml = MainListView::find()
                ->limit(10)
                ->asArray()
                ->orderBy(['date_system'=>SORT_DESC])
                ->all();
            }
            echo json_encode($ml);

        }else{
            echo json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
        }

        //echo $do->stype;
    }

    public function actionAllactions()
    {
      $postData = file_get_contents("php://input");
      $do = json_decode($postData);
      //$agent = 0;
      if(!empty($do->token) && $do->token == md5(Yii::$app->session->getId().'opn')){
        if($do->dataid){
          //try{
            $agent = isset($do->fio) ? $do->fio : 0;
            $command = Yii::$app->db->
            createCommand("SET NOCOUNT ON;EXEC dbo.actionData @pid = ".$do->dataid
            .", @status = ".$do->state
            .", @priceSold = ".$do->price_sold
            .", @soldCurrency = ".$do->sale_currency
            .", @agentId = ".$agent."");
            $data = $command->queryAll();
              //print_r($data);
              echo json_encode(['status'=>$data[0]['status'],'msg'=>$data[0]['ErrorMessage']]);
          // }catch(Exception $e){
          //     //print_r($e->errorInfo[2]);
          //     echo json_encode(['status'=>1,'data'=>null,'msg'=>$e->errorInfo[2]]);
          // }
        }else{
          echo json_encode(['status'=>1,'data'=>null,'msg'=>'Ошибка! токен не соответствует']);
        }
      }else{
          echo json_encode(['status'=>2,'data'=>null,'msg'=>'Ошибка! токен не соответствует']);
      }
    }

    public function actionSetdata()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        if($do->token === md5(Yii::$app->session->getId().'opn')){
            if(!isset($do->id) && empty($do->id)){
                $ml = new MainList();
            }else{
                $ml = MainList::findOne($do->id);
            }
            try{
                $ml->user_id = Yii::$app->user->identity->getId();
                $ml->name = $do->name;
                $ml->from_agent_id = isset($do->fio) ? $do->fio : null;
                $ml->groupby = $do->groupby;
                $ml->insertion = $do->insertion;
                $ml->sample = $do->sample;
                $ml->type_of_delivery = $do->type_of_delivery;
                $ml->size = $do->size;
                $ml->exchangerate = floatval($do->exchangerate);
                $ml->weight_grams = $do->weight_grams;
                $ml->price_buy = $do->price_buy;
                $ml->price_sale = $do->price_sale;
                $ml->price_sold = isset($do->price_sold) ? $do->price_sold : 0;
                $ml->discount = isset($do->discount) ? $do->discount : 0;
                $ml->buy_currency = $do->buy_currency;
                $ml->sale_currency = $do->sale_currency;
                $ml->date_of_arrival = date('Y-m-d\TH:i:s');
                $ml->date_of_sale = date('Y-m-d\TH:i:s');
                $ml->comment = $do->comment;
                $ml->bar_code = $do->bar_code;
                $ml->status = $do->status;
                $ml->date_system = date('Y-m-d\TH:i:s');
                $ml->save();
                return json_encode(array('status'=>1,'message'=>'good!'));
            }catch(Exception $e){
              return json_encode(array('status'=>3,'message'=>$e->errorInfo));
            }
        }else{
            return json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
        }
    }

    public function actionDeleterow()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        $ml = MainList::findOne($do->id);
        $ml->delete();
        //print_r($ml);
    }
    /* library Controllers */
    public function actionLibrary()
    {
      return $this->render('library',
                          ['sample'=> new Sample(),
                           'insertion' => new Insertion(),
                           'productGroup' => new ProductGroup(),
                           'typeOfDelivery' => new TypeOfDelivery()
                         ]);
    }

    public function actionGetlib()
    {
      header('Content-Type: application/json');
      try{
            $sample = Sample::find()->asArray()->all();
            $insertion = Insertion::find()->asArray()->all();
            $productGroup = ProductGroup::find()->asArray()->all();
            $typeOfDelivery = TypeOfDelivery::find()->asArray()->all();

            echo json_encode(['status'=>0,'data'=>[
                                       'sample' => $sample,
                                       'insertion' => $insertion,
                                       'productGroup' => $productGroup,
                                       'typeOfDelivery' => $typeOfDelivery],
                              'msg'=>'OK']
                            );
      }catch(Exception $e){
        echo json_encode(['status'=>1, 'msg'=>$e->errorInfo]);
      }
    }

    public function actionSetlib()
    {
      $postData = file_get_contents("php://input");
      $do = json_decode($postData);

      if($do->token === md5(Yii::$app->session->getId().'opn')){
          if(empty($do->dataid)){
              switch((int)$do->state){
                case 1:{ $ml = new ProductGroup(); } break;
                case 2:{ $ml = new Sample(); } break;
                case 3:{ $ml = new Insertion(); } break;
                case 4:{ $ml = new TypeOfDelivery(); } break;
              }
          }else{
            switch((int)$do->state){
              case 1:{ $ml = ProductGroup::findOne($do->dataid); } break;
              case 2:{ $ml = Sample::findOne($do->dataid); } break;
              case 3:{ $ml = Insertion::findOne($do->dataid); } break;
              case 4:{ $ml = TypeOfDelivery::findOne($do->dataid); } break;
            }
          }
              $ml->name = $do->name;
              $ml->save();
              return json_encode(array('status'=>1,'message'=>'good!'));

      }else{
          return json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
      }
    }

    public function actionTest()
    {
      $request = Yii::$app->request;
      $page = $request->get('page');
      $ml = MainListView::find();
      // //->limit(10)
      // ->asArray()
      // ->orderBy(['date_system'=>SORT_DESC])
      // ->all();
      $dt = $ml->offset($page)->limit(3)->asArray()->all();
              print_r($dt);
    }

}
