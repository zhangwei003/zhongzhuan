<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$qr_img = decrypt($_GET['qr_img']);
unset($_GET['qr_img']);
unset($_GET['remark']);
unset($_GET['user']);
$account_name = addslashes($_GET['account_name']);
$bank_name = addslashes($_GET['bank_name']);
$account_number = addslashes($_GET['account_number']);
$trade_no = addslashes($_GET['trade_no']);
$order_pay_price = addslashes($_GET['order_pay_price']);
$sign = addslashes($_GET['sign']);

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
<html class="fixed js flexbox flexboxlegacy csstransforms csstransforms3d no-overflowscrolling" style=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./static/css/bootstrap.css">
    <link rel="stylesheet" href="./static/css/font-awesome.css">
    <link rel="stylesheet" href="./static/css/theme.css">
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
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss"></head>
<body style="" class="">
<!-- start: page -->

<section class="body-sign cpBtn4" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;">
                    <img src="./static/img/dypay.png" style="width: 40%;" alt="">
                </div>
                <div class="text-center" style="font-size:16px;line-height:30px;height:40px;color:#000;margin:20px 0;">
                    <span id="ht_order_no">订单口令：<?php echo $_GET['trade_no']; ?></span><span class="copy_btn copy0" style="background: #000;color: #fff;    padding: 3px;    margin-left: 5px;    border-radius: 2px;" data-clipboard-text="<?php echo $_GET['trade_no']; ?>" onclick="copy_txt('copy0')">复制</span>
                </div>
                <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:20px 0 20px 0">
                    金额:<span id="balance" style="color: #ff0000"><?php echo $_GET['order_pay_price']; ?></span>元
                </div>
                <div class="text-center">
                    <p class="time" style="margin-top: 20px;font-size: 16px;font-weight: 900">
                        <span id="time" class="text-danger" style="opacity: 1;">抖音红包支付流程</span>
                    </p>
                </div>
                <div >
                    <span style="color:#000;font-size:1.5rem;font-weight: bold;">1.复制订单口令，截图抖音二维码，打开抖音APP。点击左上角 + 号，扫一扫。 <br></span><br>
                    <span style="color:#000;font-size: 1.5rem;font-weight: bold;">2.识别抖音二维码后，点击<span style="color: #ff0000">+关注</span>，点击私信，不需要等客服说话，直接发送<span style="color: green">订单口令</span>，然后等待客服回关，客服回关后，你只需要返回刷新一下，然后在聊天页面点击右下角<span style="color: #ff0000">+号</span>，发送对应充值金额的<span style="color: #ff0000">红包</span>即可。  <br></span>

                </div>
                <br>

                <div class="text-center">


                    <div class="text-center qrcode_img">
                        <img src="http://<?php echo $qr_img; ?>" alt="" style="width: 300px;height: 300px;object-fit: contain">
                    </div>
                    <br>
                    <div id="btn_send" style="background: #000;color: #ffffff;font-size: 1.5rem;height: 5rem;line-height: 5rem;width: 100%;margin: 0 auto;border-radius: 8px">
                        打开抖音
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
<script src="./static/js/clipboard.min.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<!--
<script src="/static/pay/qrcode.min.js?v=20220914193400001"></script>
-->
<script>
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer;
        var form = layui.form;
     window.copy_txt = function (id) {
            var clipboard = new ClipboardJS('.'+id);
            clipboard.on('success', function(e) {
                layer.msg('复制成功',{time:1500});
                // e.clearSelection();
            });
            clipboard.on('error', function(e) {
                layer.alert('复制失败，请手动复制', {icon: 5});
            });
        }
    })



    $('#btn_send').on('click',function(){

        window.location.href = 'snssdk1128://';
        return false;
    });
</script>

</body>
</html>
