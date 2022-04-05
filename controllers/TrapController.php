<?php

namespace app\controllers;

use app\models\Browserdetail;
use app\models\UserMail;
use app\models\Click;
use app\models\IpLocation;
use Yii;

class TrapController extends \yii\rest\Controller
{
    function getParam($name, $from)
    {
        if(isset($from[$name])) {
            return $from[$name];
        }
        return '';
    }

    public function actionLog()
    {
        if(Yii::$app->request->isPost) {
            $data = json_decode(file_get_contents('php://input'),true);
            if($data === null) return 'data null';
            $model = new Click();
            $model->requestId = $this->getParam('requestId',$data);
            $model->tag = json_encode($this->getParam('tag',$data));
            $model->linkedId = $this->getParam('linkedId',$data);
            $model->visitorId = $this->getParam('visitorId',$data);
            $model->visitorFound = intval(boolval($this->getParam('visitorFound',$data)));
            $model->timestamp = intval($this->getParam('timestamp',$data));
            $model->time = gmdate('d.m.Y - G:i:s', intval($this->getParam('timestamp',$data))/1000);
            $model->incognito = intval(boolval($this->getParam('incognito',$data)));
            $model->url = $this->getParam('url',$data);
            $model->clientReferer = $this->getParam('clientReferrer',$data);
            $model->ip = $this->getParam('ip',$data);
            $ipLocation = new IpLocation();
            $dataIpLocation = $this->getParam('ipLocation',$data);
            $ipLocation->city = json_encode($this->getParam('city',$dataIpLocation));
            $ipLocation->continent = json_encode($this->getParam('continent',$dataIpLocation));
            $ipLocation->country = json_encode($this->getParam('country',$dataIpLocation));
            $ipLocation->latitude = floatval($this->getParam('latitude',$dataIpLocation));
            $ipLocation->longitude = floatval($this->getParam('longitude',$dataIpLocation));
            $ipLocation->postalCode = $this->getParam('postalCode',$dataIpLocation);
            $ipLocation->subdivisions = json_encode($this->getParam('subdivisions',$dataIpLocation));
            $ipLocation->timezone = $this->getParam('timezone',$dataIpLocation);
            if($ipLocation->save()) {
                $model->ipLocationId = $ipLocation->id;
            } else {
                $model->ipLocationId = -1;
            }
            $browserDetails = new Browserdetail();
            $dataBrowserDetails = $this->getParam('browserDetails',$data);
            $browserDetails->name = $this->getParam('browserName',$dataBrowserDetails);
            $browserDetails->fullVersion = $this->getParam('browserFullVersion',$dataBrowserDetails);
            $browserDetails->majorVersion = $this->getParam('browserMajorVersion',$dataBrowserDetails);
            $browserDetails->os = $this->getParam('os',$dataBrowserDetails);
            $browserDetails->osVersion = $this->getParam('osVersion',$dataBrowserDetails);
            $browserDetails->device = $this->getParam('device',$dataBrowserDetails);
            $browserDetails->userAgent = $this->getParam('userAgent',$dataBrowserDetails);
            if($browserDetails->save()) {
                $model->browserDetailsId = $browserDetails->id;
            } else {
                $model->browserDetailsId = -1;
            }
            $model->confidenceScore = floatval($data['confidence']['score']);

            if($model->save()) {
                return true;
            } else {
                return $model->errors;
            }
        }
        return false;
    }

    public function actionLogmail() {
        if(Yii::$app->request->isPost) {
            parse_str(file_get_contents('php://input',true),$data);
            $mail = new UserMail();
            $mail->name = $this->getParam('name', $data);
            $mail->email = $this->getParam('email', $data);
            $mail->time = 0;
            if(!$mail->save()){
                return $mail->errors;
            }
            return $data;
        }
        return 'not a post';
    }

}
