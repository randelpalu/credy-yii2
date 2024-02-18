<?php

namespace app\controllers;

use app\components\SignatureGenerator;
use app\models\CredyApplicationForm;
use app\services\CredyApplicationServiceInterface;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
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
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
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

    /**
     * Displays credy-application page.
     *
     * @param CredyApplicationServiceInterface $applicationService
     * @return string
     */
    public function actionCredyApplication(CredyApplicationServiceInterface $applicationService): string
    {
        $model = new CredyApplicationForm();

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $timestamp = time();
            $signature = SignatureGenerator::generateSignature($timestamp);

            $data = [
                'application' => $model->attributes,
                'timestamp' => (int) $timestamp,
                'signature' => $signature
            ];

            try {
                $response = $applicationService->sendApplication($data);

                return $this->render(
                    'credy-application-confirm',
                    ['model' => $model, 'timestamp' => $timestamp, 'signature' => $signature, 'response' => $response]
                );
            } catch (\Exception $e) {
                return $this->render('error', ['name' => 'Error', 'message' => $e->getMessage()]);
            }
        } else {
            return $this->render('credy-application', ['model' => $model]);
        }
    }
}
