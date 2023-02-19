<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
$qr_img = decrypt($_GET['qr_img']);
$is_pay_name = $_GET['is_pay_name'];
unset($_GET['is_pay_name']);
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
<!-- saved from url=(0115)http://cash.huangguapay.com/c/api/pay?osn=2022122917140224252839458&t=1672305242&k=c9e5ee38d90b3980fda6e8e334840bd7 -->
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
    <script src="./static/js/clipboard.min.js"></script>
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


    </style>
    <title>支付</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="./static/css/layer.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss"></head>
<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;" >
                <img src="./static/img/pic.png" style="width: 50%;" alt="">
            </div>
            <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:10px 0 20px 0">
                ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>

                <!-- <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
            </div>
            <!--<div class="text-center">
    <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">
        支付倒计时：<span id="time" class="text-danger">00:00</span>
    </p>
</div>-->

            <!--<div style="font-size:18px;font-weight:900;color:red;margin-top:15px;">
                充值教程：
            </div>-->

            <div class="text-center" style="font-weight: bold;font-size:18px;line-height:20px;height:20px;color:#ff0000;margin:15px 0;">
                <!-- <p  style="color:#0c8c0c">请复制以下6位订单号发送到群里<span style="color:#0c8c0c">进群先核对群主名字，群主说发再发包 ，否则后果自负</span></p>
                 <p  style="color:#0c8c0c">请复制以下6位订单号发送到群里 <span style="color:#0c8c0c">进群先核对群主名字，群主说发再发包 ，否则后果自负</span></p>-->
                <p style="color:#0c8c0c">请发送以下口令到群里<!--,<span style="color:#0c8c0c">进群先核对群主名字，群主说发再发包 ，否则后果自负</span>--></p>

                <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
            </div>
            <div class="text-center" style="font-weight: bold;font-size:16px;line-height:40px;height:40px;color:#ff0000;margin:10px 0;">
                <span id="ht_order_no">口令：<span id="ht_order_number"><?php echo $orderId; ?></span></span>
                <span class="copy_btn copy0" style="background: red;color: #fff;    padding: 3px;    margin-left: 5px;    border-radius: 2px;" data-clipboard-text="<?php echo $orderId; ?>" onclick="copy_txt('copy0')">复制</span>
            </div>

                <div class="text-center qrcode_img">

                </div>
            <div class="text-center" style="font-weight: bold;font-size:16px;line-height:20px;height:30px;margin:15px 0;">
                <p>打开微信扫一扫加入群聊</p>
            </div>

            <div style="font-size:14px;color:red;margin-top:12px;font-weight: bold;">
                <p> 1.请一定要复制<span style="color: red">口令<span>！</p>
                <p> 2.请扫码添加微信，一定要发送<span style="color: red">口令<span>！</p>
                <p> 3.每个二维码只可以付款一次，<span style="color: red">请勿重复付款</span></p>
                <p> 4.订单完成后，请主动删除好友！</p>
                <p> 5.请一定要按照订单金额打款！</p>
            </div>

            <div class="kltips"></div>


        </div>
    </div>
    </div>
</section>
<div id="dialog_tips" style="display:none;">
    <span style="font-size: 2rem;font-weight: bold;color: #423f3f;">
   <p style="text-align: center;font-size: 2.2rem;color:#ff5722;"> 充值说明 </p>
        <br>
   <p style="text-align: center;color:red;">  注意！注意！注意！</p>
       <br>
        <p> 1.请一定要复制<span style="color: red">口令<span>！</p>
        <p>2.请扫码添加微信，一定要发送<span style="color: red">口令<span>！</p>
        <!-- <p>3.群里超过<span style="color: red">3</span>个人，肯定有<span style="color: red">骗子</span>，请不要发<span style="color: red">红包</span></p>-->
        <p>3.每个二维码只可以付款一次，<span style="color: red">请勿重复付款</span></p>
         <p>4.请一定要按照订单金额打款！</p>
    </span>
</div>

<div id="charge_tips" style="display:none;">
    <span style="text-align: center;font-size: 2.6rem;font-weight: bold;color: #423f3f;">
   <p style="text-align: center;font-size: 2.2rem;color:#ff5722;"> 充值说明 </p>
        <br>
   <p style="text-align: center;color:red;">  注意！注意！注意！</p>
       <br>
        <p> 1.请一定要复制<span style="color: red">口令<span>！</p>
        <p>2.请扫码添加微信，一定要发送<span style="color: red">口令<span>！</p>
        <!-- <p>3.群里超过<span style="color: red">3</span>个人，肯定有<span style="color: red">骗子</span>，请不要发<span style="color: red">红包</span></p>-->
        <p>3.每个二维码只可以付款一次，<span style="color: red">请勿重复付款</span></p>
         <p>4.请一定要按照订单金额打款！</p>
    </span>
</div>

<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/xss.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<script>
    var url = '<?php echo $qr_img; ?>';
    console.log(url);

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


</script>
<script>
    var timeLimit = 0;
    function jump() {
        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn:"知道了",
            shadeClose:false,
            yes: function(index, layero){
                layer.close(index);
                return;
                // charge_tips();
                setTimeout(function(){
                    setTimer();
                },100);

                var timer = null;
                var d_time = 3;
                function secTrans(sec){
                    var d_sec=sec%60;
                    if(d_sec<0){
                        d_sec=0;
                    }
                    var d_time_flag= d_sec;
                    return d_time_flag;
                }
                function setTimer(){
                    if(timer){
                        clearInterval(timer);
                    }
                    var d_time_flag = secTrans(3);

                    $('#layui-m-layer1').find('.layui-m-layerbtn').find('span').html('知道了(' + d_time_flag + ')');
                    timer = setInterval(function(){
                        d_time--;
                        if(d_time<0){
                            clearInterval(timer);
                        }
                        var d_time_flag=secTrans(d_time);
                        if (d_time_flag > 0){
                            d_time_flag = '(' + d_time_flag + ')';
                        } else {
                            d_time_flag = '';
                        }
                        $('#layui-m-layer1').find('.layui-m-layerbtn').find('span').html('知道了' + d_time_flag );
                    },1000);
                }



                setTimeout(function(){
                    timeLimit = 3;
                    layer.close(index);
                }, 3000);
            },
        })
    }
    function charge_tips() {
        var index = layer.open({
            content: $('#charge_tips').html(),
            btn: ['知道了'],
            shadeClose:false,
            yes: function(index, layero){
                if (timeLimit == 3) {
                    layer.close(index)
                }
            }
        })
    }
    jump();
</script>


</body></html>
