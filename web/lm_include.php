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
$lmt_gtag = $lmt_gtag_noscript = "";
$gtid = $tblGetParam("gtgid", false);
$gtvariant = $tblGetParam("gtgvar", false);
if($gtid) {
    $lmt_gtag = "<!-- Google Tag Manager -->
    <script>var lmt_variant='$gtvariant';</script>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','$gtid');</script>
    <!-- End Google Tag Manager -->";
    $lmt_gtag_noscript = "<!-- Google Tag Manager (noscript) -->
    <noscript><iframe src='https://www.googletagmanager.com/ns.html?id=$gtid'
    height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->";
}
