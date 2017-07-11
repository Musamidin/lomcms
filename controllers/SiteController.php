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
use app\models\Sample;
use app\models\Insertion;
use app\models\TypeOfDelivery;
use app\models\ProductGroup;
use yii\db\BaseActiveRecord;
//use app\componets\Init;

class SiteController extends Controller
{

    public function beforeAction($action)
    {        
        if (\Yii::$app->getUser()->isGuest && $action->id !== 'login' && $action->id !=='/'){
            Yii::$app->response->redirect(Url::to(['login']), 301);
            Yii::$app->end();
        }elseif($action->id === 'setdata'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'getdata'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'deleterow'){
            $this->enableCsrfValidation = false;
        }elseif($action->id === 'search'){
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $mainForm = new MainForm();
        $mainlist = MainListView::find()->all();
        return $this->render('index',
                            ['mainlist' => $mainlist,
                             'mainForm' => $mainForm
                            ]);
    }
    /**
     * Login action.
     *
     * @return string
     */
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
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('login');
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionConsumption()
    {
        $cons = Consumption::find();

        $pagination = new Pagination([
            'defaultPageSize'=>4,
            'totalCount'=>$cons->count()
            ]);
        $cons = $cons->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        return $this->render('consumption',[
            'cons'=>$cons,
            'pagination'=>$pagination
            ]
            );
    }

    public function actionGetdata()
    {
        header('Content-Type: application/json');

        $sample = Sample::find()->asArray()->all();
        $insertion = Insertion::find()->asArray()->all();
        $productGroup = ProductGroup::find()->asArray()->all();
        $typeOfDelivery = TypeOfDelivery::find()->asArray()->all();
        $ml = MainListView::find()
        ->limit(2)
        ->asArray()
        ->orderBy(['date_system'=>SORT_DESC])
        ->all();
        echo json_encode(
                        array(
                            'mainlist' => $ml,
                            'sample' => $sample,
                            'insertion' => $insertion,
                            'productGroup' => $productGroup,
                            'typeOfDelivery' => $typeOfDelivery
                            )
                        );
    }
    public function actionSearch()
    {
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);

        header('Content-Type: application/json');
        /*
        if($do->token === md5(Yii::$app->session->getId().'opn')){
            if(!empty($do->key)){
                $ml = MainListView::find()
                    ->filterWhere(['LIKE', 'name', $do->key])
                    ->asArray()
                    ->orderBy(['date_system'=>SORT_DESC])
                    ->all();
            }else{
                $ml = MainListView::find()
                ->limit(2)
                ->asArray()
                ->orderBy(['date_system'=>SORT_DESC])
                ->all();
            }
            echo json_encode($ml);

        }else{
            echo json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
        }
        */
        echo $do->stype;
    }

    public function actionSetdata(){

        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        
        if($do->token === md5(Yii::$app->session->getId().'opn')){
            if(empty($do->id)){
                $ml = new MainList();
            }else{
                $ml = MainList::findOne($do->id);
            }
                $ml->user_id = Yii::$app->user->identity->getId();
                $ml->name = $do->name;
                $ml->groupby = 1;
                $ml->insertion = 1;
                $ml->sample = $do->sample;
                $ml->type_of_delivery = $do->type_of_delivery;
                $ml->size = $do->size;
                $ml->count = $do->count;
                $ml->weight_grams = 1;
                $ml->price_buy = 1;
                $ml->price_sale = 1;
                $ml->price_sold = 1;
                $ml->discount = 1;
                $ml->buy_currency = 1;
                $ml->sale_currency = 1;
                $ml->date_of_arrival = date('Y-m-d\TH:i:s');
                $ml->date_of_sale = date('Y-m-d\TH:i:s');
                $ml->comment = 'testim';
                $ml->bar_code = $do->bar_code;
                $ml->status = 1;
                $ml->date_system = date('Y-m-d\TH:i:s');
                $ml->save();
                return json_encode(array('status'=>1,'message'=>'good!'));
            
        }else{
            return json_encode(array('status'=>2,'message'=>'Error(Invalid token!)'));
        }   
    }
    public function actionDeleterow(){
        $postData = file_get_contents("php://input");
        $do = json_decode($postData);
        $ml = MainList::findOne($do->id);
        $ml->delete();
        //print_r($ml);
    }
}
