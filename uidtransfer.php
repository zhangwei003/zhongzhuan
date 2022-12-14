<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
unset($_GET['user']);
$account_name = addslashes($_GET['account_name']);
$bank_name = addslashes($_GET['bank_name']);
$account_number = addslashes($_GET['account_number']);
$trade_no = addslashes($_GET['trade_no']);
$order_pay_price = addslashes($_GET['order_pay_price']);
$sign = addslashes($_GET['sign']);
//$url = 'https://www.alipay.com/?appId=20000123&actionType=scan&biz_data={"s":"money","u":"'.decrypt($account_number).'","a":"'.$order_pay_price.'","m":"商城购物'.$trade_no.'"}';
$gourl = 'alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount='.$order_pay_price.'&userId='.decrypt($account_number).'&memo='.$trade_no;
//'alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount=199.99&userId=2088105516362581&memo=64748540';
//only get params
$is_bzk=$_GET['is_bzk'];
$paramsKeys = ['account_name', 'is_bzk','bank_name', 'account_number', 'trade_no', 'order_pay_price', 'sign'];
//$gourl = $_GET['gourl'];
//unset($_GET['gourl']);
$keyDifs = array_diff(array_keys($_GET), $paramsKeys);
if ($keyDifs) {
    die("访问异常1");
}
foreach ($paramsKeys as $key => $val) {
    if (!array_key_exists($val, $_GET) || empty($_GET[$val])) {
//        die("访问异常2");
    }
}

//验证签名
$inner_transfer_secret = 'g8CZvkqwwFRmKyloOAc2hLAZgZg8Ahcz';
$sign = getSign(array_merge($_GET, ['key' => $inner_transfer_secret]));
if ($sign !== $_GET['sign']) {
    //  die("访问异常3");
}
//记录用户访问相关信息以及安全季校验
$data['trade_no'] = $_GET['trade_no'];
$data['visite_ip'] = getRealIp();
$data['visite_clientos'] = clientOS();
$ret = json_decode(httpRequest($returl, 'post', $data), true);
if ($ret['code'] != 1) {
}
?>
<!DOCTYPE html>
<!-- saved from url=(0115)http://cash.huangguapay.com/c/api/pay?osn=2022121417042066064748540&t=1671008660&k=c367297259c4a6ca74d2b535215ca78e -->
<html class="fixed js flexbox flexboxlegacy csstransforms csstransforms3d no-overflowscrolling" style=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./static/css/bootstrap.css">
    <link rel="stylesheet" href="./static/css/font-awesome.css">
    <!-- <link rel="stylesheet" href="/static/pay/magnific-popup.css?v=20221112003800001">-->
    <!--<link rel="stylesheet" href="/static/pay/datepicker3.css?v=20221112003800001">-->
    <!-- Theme CSS -->
    <link rel="stylesheet" href="./static/css/theme.css">
    <!-- Skin CSS -->
    <!-- <link rel="stylesheet" href="/static/pay/default.css?v=20221112003800001">-->
    <!-- Theme Custom CSS -->
    <!-- <link rel="stylesheet" href="/static/pay/theme-custom.css?v=20221112003800001">-->

    <!-- Head Libs -->
    <!-- <script src="/static/pay/modernizr.js?v=20221112003800001"></script>-->
    <style>
        .login span {
            line-height: 50px;
            font-weight: 900;
        }

        .btn-info{
            background: #027AFF !important;
            border-color: #027AFF !important;
        }
        .kltips {
            font-size: 1.8rem;
            color: #f30;
            padding: 1rem;
            text-align: center
        }
        .center-sign .warmTips{color: #f58807;border-top: 1px solid #f2f2f2;margin-top: 1.2rem;padding: 0 4%;line-height: 1.7rem;}
        .center-sign .warmTips h3{padding: 1rem 0 0.5rem;}
        .center-sign .warmTips p{}
        #layui-m-layer0 .layui-m-layerbtn {display:box;display:-moz-box;display:-webkit-box;width:100%;
            height:50px;line-height:50px;font-size:0;border-top:1px solid #D0D0D0;
            background-color:#1b76fc;
        }
        #layui-m-layer0 .layui-m-layercont {
            padding: 30px 20px;
            line-height: 22px;
            text-align: left;
        }
    </style>
    <title>支付</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="./static/css/layer.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss"><link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss"></head>
<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;" "="">
                <img src="./static/img/alipay.png" style="width: 40%;" alt="">
            </div>
            <div class="text-center" style="font-size:16px;line-height:30px;height:40px;color:#000;margin:20px 0;">
                <span id="ht_order_no">订单号：<?php echo $_GET['trade_no']; ?></span>

                <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
            </div>
            <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:20px 0 20px 0">
                ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none;font-size:14px;" data-clipboard-action="copy" data-clipboard-target="#balance">复制金额</button>
                <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
            </div>
            <div class="text-center">
                <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">
<!--                    支付倒计时：-->
                    <span id="time" class="text-danger" style="opacity: 1;">请在5分钟内支付</span>
                </p>
            </div>
            <div class="text-center">
                <p class="" style="color: red; margin-top: 20px;font-size: 16px;font-weight: 900">
                    收款人：<?php echo decrypt($_GET['account_name']); ?></p>
            </div>
            <br>
            <div class="text-center">
                <span style="color:#ff0000;font-size:1.5rem;font-weight: bold;">请在有效时间内按上方订单金额支付 <br></span>
                <span style="color:#ff0000;font-size: 1.5rem;font-weight: bold;">此码只能付款一次，如因超时支付、重复支付、修改金额导致的损失需自已承担，平台不退不补！！！  <br></span>
            </div>
            <br>



            <div class="kltips"></div>

            <div class="text-center">
                <div id="btn_send" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 5rem;line-height: 5rem;width: 100%;margin: 0 auto;border-radius: 8px">
                    点击启动支付宝付款
                </div>
                <br>
                <div class="text-center qrcode_img">

                </div>
                <div class="text-center" style="font-weight: bold;font-size:16px;line-height:20px;height:40px;margin:15px 0;">
                    <p>如果无法打开付款界面，请截屏保存二维码，到支付宝扫一扫付款！</p>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<div id="dialog_tips" style="display:none;">
    <span style="text-align: center;font-size: 1.8rem;font-weight: bold;color: #423f3f;">

   <p style="text-align: center;color:red;"> ⚠️⚠️⚠️ 注意注意注意⚠️⚠️⚠️</p>
    <br>
    <p style="text-align: center;color:red;">请勿使用亲情卡付款</p>
    <p style="text-align: center;color:red;">无法到账，损失自已承担</p>
    </span>
</div>
<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<!--
<script src="/static/pay/qrcode.min.js?v=20220914193400001"></script>
-->
<script>

    var url = '<?php echo $gourl; ?>';
    //生成二维码
    function getQrcode(url,qrcode_with=300,qrcode_height=300){
        $(".qrcode_img").qrcode({
            render: "canvas",
            width:300,
            height:300,
            text: decodeURIComponent(url)
        });
        $('#image').hide();
        $('.qrcode_img').find('canvas').css({'width':qrcode_with,'height':qrcode_height});
    }
    getQrcode(encodeURIComponent(url));


    $('#btn_send').on('click',function(){

        window.location.href = url;
        return false;
    });
</script>

</body>
</html>
