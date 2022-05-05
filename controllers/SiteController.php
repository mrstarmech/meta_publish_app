<?php

namespace app\controllers;

use yii\web\Controller;
use app\common\GetClick;
use Yii;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($id=0)
    {
        include '../../storage/clo_list.php';
        $params = Yii::$app->requestedParams;
        if(isset($params["id"])) {            
            if (isset($strm_cloaks[$params['id']])) {
                $click = new GetClick($strm_cloaks[$params['id']], $BnmApiKey($strm_cloaks[$params['id']]));
                if($click instanceof GetClick && array_key_exists('path', $click->DataClick) && $click->DataClick['path']['name'] !== 'White') {
                    $plurl = '';
                    if($click->getLandingUrl() == 'Direct') {
                        $plurl = parse_url($click->getOfferUrl());
                    } else {
                        $plurl = parse_url($click->getLandingUrl());
                    }
                    $path = "/../../storage".$plurl["path"];
                    $query = $plurl["query"].$click->DataClick["campaign"]["campaign_land_tokens"];
                    if($query) {
                        parse_str($query, $query_params);
                    }
                    $this->layout = false;
                    return $this->render('indexpl',['root_fld'=>$path, 'path_to_pl'=>Yii::getAlias('@webroot').$path."index.php", 'query_params'=>$query_params]);
                }
            }
        }
        return $this->render('index');
    }

    public function actionOk() {
        return 'ok';
    }
}
