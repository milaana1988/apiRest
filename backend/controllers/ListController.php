<?php

namespace backend\controllers;
use app\models\TblListData;
use yii\filters\AccessControl;
use yii\filters\Cors;

class ListController extends \yii\web\Controller
{
    private $authKey;
    private $sentKey;



    public static function allowedDomains() {
        return [
            // '*',                        // star allows all domains
            'http://localhost:4200',
        ];
    }
    
    public function checkAuth(){
        $headers = \Yii::$app->request->headers;
        $this->authKey = 'Bearer cmVzdGFwaTp0ZW1wcGFzcw==';
        $headerKey = $headers['authorization'];

        if($headerKey == $this->authKey){
            return true;
        }
        return false;
    }
        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {

                return array_merge([
                    'cors' => [
                        'class' => Cors::className(),
                        #special rules for particular action
                        'actions' => [
                            'get' => [
                                'Origin' =>['*'],
                                'Access-Control-Request-Method' => ['GET'],
                                'Access-Control-Request-Headers' => ['*'],
                                'Access-Control-Allow-Credentials' => null,
                                'Access-Control-Max-Age' => 86400,
                                'Access-Control-Expose-Headers' => [],

                            ],
                            'create' => [
                                'Origin' => ['*'] ,
                                'Access-Control-Request-Method' => ['POST','OPTIONS'],
                                'Access-Control-Request-Headers' => ['*'],
                                'Access-Control-Allow-Credentials' => null,
                                'Access-Control-Max-Age' => 86400,
                                'Access-Control-Expose-Headers' => [],
                            ],
                            'delete' => [
                                'Origin' => ['*'] ,
                                'Access-Control-Request-Method' => ['DELETE','OPTIONS'],
                                'Access-Control-Request-Headers' => ['*'],
                                'Access-Control-Allow-Credentials' => null,
                                'Access-Control-Max-Age' => 86400,
                                'Access-Control-Expose-Headers' => [],
                            ],
                            'update' => [
                                'Origin' => ['*'],
                                'Access-Control-Request-Method' => ['PUT','OPTIONS'],
                                'Access-Control-Request-Headers' => ['*'],
                                'Access-Control-Allow-Credentials' => null,
                                'Access-Control-Max-Age' => 86400,
                                'Access-Control-Expose-Headers' => [],
                            ],
                        ],
                        #common rules
                        'cors' => [
                            'Origin' =>  [],
                            'Access-Control-Request-Method' => [],
                            'Access-Control-Request-Headers' => [],
                            'Access-Control-Allow-Credentials' => null,
                            'Access-Control-Max-Age' => 0,
                            'Access-Control-Expose-Headers' => [],
                        ],

                    ],
                ], parent::behaviors());


        }

    public function actionCreate()
   {
       \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
       if($this->checkAuth()){
           $listData = new TblListData();
           $listData->scenario = TblListData:: SCENARIO_CREATE;
           $listData->attributes = \Yii::$app->request->post();

           if($listData->validate())
           {
               $listData->save();
               return array('status' => true, 'data'=> 'Task record is successfully updated', 'id'=>$listData->id);
           }
           else
           {
               return array('status'=>false,'data'=>$listData->getErrors());
           }
       }
       return array('status'=>false,'data'=>'Connection denied.');

    }

    public function actionGet()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        if($this->checkAuth()) {

            $listData = TblListData::find()->all();
            if (count($listData) > 0) {
                return array('status' => true, 'data' => $listData);
            } else {
                return array('status' => false, 'data' => 'No Task Found');
            }
        }
        return array('status'=>false,'data'=>'Connection denied.');
    }

    public function actionUpdate($id)
   {
       \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
       if($this->checkAuth()) {
           $listData = TblListData::find()->where(['id' => $id])->one();
           $listDataArr = $listData->toArray();

           if (count($listDataArr) > 0) {
               $listData->attributes = \yii::$app->request->post();
               $listData->save();
               return array('status' => true, 'data' => 'Task record is updated successfully');

           } else {
               return array('status' => false, 'data' => 'No Task Found');
           }
       }
       return array('status'=>false,'data'=>'Connection denied.');
   }

   public function actionDelete($id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        if($this->checkAuth()) {
            $listData = TblListData::find()->where(['id' => $id])->one();
            $listDataArr = $listData->toArray();
            if (count($listDataArr) > 0) {
                $listData->delete();
                return array('status' => true, 'data' => 'Task record is successfully deleted');
            } else {
                return array('status' => false, 'data' => 'No Task Found');
            }
        }
        return array('status'=>false,'data'=>'Connection denied.');
    }
}
