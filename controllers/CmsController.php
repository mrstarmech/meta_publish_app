<?php

namespace app\controllers;

use app\models\Article;
use app\models\ArticleCategory;
use app\models\ArticleContent;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;
use yii\web\UploadedFile;

class CmsController extends Controller
{
    public $layout = 'cms';
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        if(Yii::$app->user->isGuest) return $this->redirect('/cms/login');
        $articles = Article::find()->all();
        return $this->render('index',['articles'=>$articles]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/cms/index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(Yii::$app->user->id === '102') {
                return $this->redirect('/logc');
            }
            return $this->redirect('/cms/index');
        } 

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if(Yii::$app->user->isGuest) return $this->redirect('/cms/login');

        Yii::$app->user->logout();

        return $this->redirect('index');
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest) return $this->redirect('/cms/login');

        $model = new Article();
        $categories = ArticleCategory::find()->all();
        $content = new ArticleContent();
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'thumbnail');
            $t = time();
            if($file) {
                $path = Yii::getAlias("@webroot") . "/img/article_thumbs/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $file->saveAs($path.$t."$file->name",false);
                $model->thumbnail = "/img/article_thumbs/$t"."$file->name";
            }

            $content->content = Yii::$app->request->post()["Article"]["actual_content"];
            if(!$content->save()) {
                Yii::$app->session->setFlash('error', 'Can\'t save content<br>' . json_encode($content->errors));
                return $this->render('create', ['model'=>$model,'cats'=>$categories]);
            }
            $model->content_id = $content->id;
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Model saved<br>');
                return $this->redirect('/cms/index');
            } else {
                Yii::$app->session->setFlash('error', 'Can\'t save model<br>' . json_encode($model->errors));
                return $this->render('create', ['model'=>$model,'cats'=>$categories]);
            }
        }
        return $this->render('create', ['model'=>$model,'cats'=>$categories]);
    }

    public function actionEdit($id) {
        if(Yii::$app->user->isGuest) return $this->redirect('/cms/login');

        $model = Article::findOne($id);
        $content = ArticleContent::findOne($model->content_id);
        $categories = ArticleCategory::find()->all();
        $model->actual_content = $content->content;
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_input');
            $t = time();
            if($file) {
                $path = Yii::getAlias("@webroot") . "/../../storage/img/article_thumbs/";
                if (!is_dir($path)) {
                    mkdir($path, 0666, true);
                }
                $file->saveAs($path.$t."$file->name",false);
                $model->thumbnail = "/storage/img/article_thumbs/$t"."$file->name";
            }
            $files = UploadedFile::getInstances($model, 'imageFiles');
            foreach ($files as $f) {
                $path = Yii::getAlias("@webroot") . "/../../storage/img/uploads/$model->id/";
                if (!is_dir($path)) {
                    mkdir($path, 0666, true);
                }
                $f->saveAs($path."$f->name",false);
                $model->has_files = true;
            }
            if($model->has_files) $content->content = $this->process_img(Yii::$app->request->post()["Article"]["actual_content"], $model->id);
            else $content->content = Yii::$app->request->post()["Article"]["actual_content"];
            if(!$content->save()) {
                Yii::$app->session->setFlash('error', 'Can\'t save content<br>' . json_encode($content->errors));
                return $this->render('edit', ['model'=>$model,'cats'=>$categories]);
            }
            $model->content_id = $content->id;
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Model saved<br>');
                return $this->redirect('/cms/index');
            } else {
                Yii::$app->session->setFlash('error', 'Can\'t save model<br>' . json_encode($model->errors));
                return $this->render('edit', ['model'=>$model,'cats'=>$categories]);
            }
        }
        return $this->render('edit', ['model'=>$model,'cats'=>$categories]);
    }

    public function actionDelete($id) {
        $toDelete = Article::findOne($id);
        $content = $toDelete->content;
        $toDelete->delete();
        $content->delete();
        return $this->redirect('/cms/index');
    }

    private function process_img($html, $id) {
        
        return preg_replace_callback('/(<img.+src=)([\",\'].+?[\",\'])(.*?>)/i',function($matches)use($id){
            $matches[1] = preg_replace('/(width|height)=[\'\"].*?[\'\"]/i', '', $matches[1]);
            $matches[3] = preg_replace('/(width|height)=[\'\"].*?[\'\"]/i', '', $matches[3]);
            $src = [];
            preg_match('/([\w\-\+\=\$\#\%\&\?\^\(\)\:\;\<\>\!\~\`\@\d]*?\.\w+)/i',$matches[2],$src);
            return $matches[1]."'/storage/img/uploads/$id/$src[1]'".$matches[3];
        },$html);
    }
}
