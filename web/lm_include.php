<?php

/**
 * @var Array $query_params //equals to $_GET if not set explicitly
 */
include $_SERVER["DOCUMENT_ROOT"] . "/../../config.php";

$tblGetParam = function ($name, $default = "") use ($query_params) {
    return isset($query_params[$name]) ? $query_params[$name] : $default;
};

$domain         = $tblGetParam("domain", "tblkngs.com");
$uclick         = $tblGetParam('uclick', false);
$clickPhpUrl    = "//$domain/click.php";
$offerLink      = "//$domain/click.php?lp=1" . ($uclick ? "&uclick=$uclick" : "");
$umg_params     = json_encode($query_params); //params for ultramagic in case of param-free url
$pname          = $tblGetParam("pname", "keto_100500");

include "$product_include_path/$pname/product.php";

$product_relative_path = "$storage/$pname/";
$design_relative_path  = $root_fld;
$get_date = function($format, $offset = 0) {
    $o = $offset * 86400;
    return date($format, time() + $o);
};
