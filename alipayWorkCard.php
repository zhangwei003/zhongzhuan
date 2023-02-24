<?php
include_once './tools.php';
$user = $_GET['user'];
$orderId = 20000000 + $_GET['remark'];
$is_pay_name = $_GET['is_pay_name'];
$gourl = decrypt($_GET['qr_img']);
unset($_GET['qr_img']);
unset($_GET['is_pay_name']);
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
$data['key'] = $user;
$ret = json_decode(httpRequest(RECORD_USER_VISITE_INFO, 'post', $data), true);
if ($ret['code'] != 1) {
}

?>
<!DOCTYPE html>
<!-- saved from url=(0115)http://pay2.newcucumber.com/c/api/pay?osn=2023011716552672684037626&t=1673945726&k=a983ff476ef47e938225a371aa71ff5f -->
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
    <script src="./static/js/clipboard.js"></script>
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

        .body-sign .panel-sign .panel-body {
            background: #FFF;
            border-top: 5px solid #cccccc;
            border-radius: 5px 0 5px 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            padding: 20px 33px 15px;
        }
        #layui-m-layer0 .layui-m-layerbtn {display:box;display:-moz-box;display:-webkit-box;width:100%;
            height:50px;line-height:50px;font-size:0;border-top:1px solid #D0D0D0;
            background-color:#1b76fc;
        }
        #layui-m-layer0 .layui-m-layercont {
            padding: 30px 20px;
            line-height: 22px;
            text-align: left;
        }

        #layui-m-layer1 .layui-m-layerbtn {display:box;display:-moz-box;display:-webkit-box;width:100%;
            height:50px;line-height:50px;font-size:0;border-top:1px solid #D0D0D0;
            background-color:#1b76fc;
        }
        #layui-m-laye1  .layui-m-layercont {
            padding: 30px 20px;
            line-height: 22px;
            text-align: left;
        }

    </style>
    <title>支付宝工作证收银台</title>
    <link href="./static/layui/css/layui.css" rel="stylesheet" />
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">

<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;">
                <img src="./static/img/alipay_logo.png" alt="">
            </div>
            <div class="text-center" style="font-weight: bold;font-size:18px;line-height:30px;height:30px;margin:10px 0;">
                <span><span><?php echo $_GET['trade_no']; ?></span></span>
            </div>

            <div class="text-center" style="font-size:38px;line-height:35px;height:35px;color:#000;margin:15px 0 15px 0">
                ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                <button type="button" class="btn btn-info btn-sm copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>
            </div>

<!--            <div class="text-center">-->
<!--                <p class="" style="margin-top: 10px;font-size: 16px;font-weight: 900">-->
<!--                    支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">04分32秒</span>-->
<!--                </p>-->
<!--                <br>-->
<!--            </div>-->
            <p style="text-align:center;color:#ff0000;font-size:1.6rem;line-height: 27px;font-weight: bold;">
                跳转选择【向Ta转账】，输入付款金额并确认转账!
                修改金额，超时支付，导致充值失败，概不负责!
            </p>

            <!--
            <div class="text-center" style="font-weight: bold;font-size:16px;line-height:40px;height:40px;color:#ff0000;margin:20px 0;">
                <span id="ht_order_no">识别码：<span id="ht_order_number">037626</span></span>

                &nbsp;&nbsp;<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none;font-size:14px;" data-clipboard-action="copy" data-clipboard-target="#ht_order_number">复制</button>
            </div>-->

            <br>

            <div class="text-center">
                <div id="btn_send_jump" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 4rem;line-height: 4rem;width: 90%;margin: 0 auto;border-radius: 4px">
                    点击启动支付宝付款
                </div>
            </div>
            <br>
            <div class="text-center qrcode_img">

            </div>
            <br>
            <div class="text-center">
                <p style="font-size: 16px;font-weight: 900">
                    PC用户请使用支付宝扫描二维码完成支付
                </p>
            </div>

            <div style="text-align: center;margin-top:10px;">
                <!-- <img src="/static/pay/alipay/alipay_pocket.jpg?v=20221112003800001" style="width: 90%;" alt="">-->
            </div>
        </div>
    </div>
    </div></section>



<div id="dialog_tips2" style="display:none;">
    <p style="text-align: center;color:#0b0b0b;  font-size: 16px;font-weight: bold;">本单只需支付   </p>
    <p style="text-align: center;color:#0b0b0b;  font-size: 28px;font-weight: bold;height: 30px;"> <span style="color:red"> <?php echo $_GET['order_pay_price']; ?> </span>   </p>
    <p style="text-align: left;color:#0b0b0b; font-size: 16px;line-height: 29px;font-weight: bold;">一定要复制金额粘贴到付款界面完成付款，严格按照提示金额付款，否则不回调，不补单，不查单，损失自行承担，切记切记！</p>
</div>

<div id="dialog_tips" style="display:none;">
    <span style="font-size: 2rem;font-weight: bold;">
   <p style="text-align: center;font-size: 1.8rem;color:#000000;line-height: 3rem;"> 请填写您的付款支付宝昵称</p>
   <p style="text-align: center;color:red;font-size: 1.8rem;"> 请正确填写，否则无法到账</p>
   <p style="text-align: center;color:#000000;font-size: 1.6rem;line-height: 3rem;">
       <input type="text" placeholder="请输入支付宝昵称" id="txt_pay_name" value="" style="text-align:center; border: 1px solid #bcb4b4;">
   </p>
    </span>

</div>


<script src="./static/js/jquery.js"></script>
<script src="./static/layui/layui.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<script>
    var url = '<?php echo $gourl; ?>';
    //生成二维码
    function getQrcode(url,qrcode_with=200,qrcode_height=200){
        $(".qrcode_img").qrcode({
            render: "canvas",
            width:200,
            height:200,
            text: decodeURIComponent(url)
        });
        // $('#image').hide();
        $('.qrcode_img').find('canvas').css({'width':qrcode_with,'height':qrcode_height});
    }
    getQrcode(encodeURIComponent(url));

    var clipboard6 = new Clipboard('.copy2');
    clipboard6.on('success', function (e) {
        layer.open({
            content: "复制成功",
            skin: "msg",
            time: 3,
        });

    });
    clipboard6.on('error', function (e) {
        layer.open({
            content: "复制失败",
            skin: "msg",
            time: 3,
        });

    });

</script>
<script>
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer;
        var form = layui.form;


        window.save_payname = function (pay_name,index){
            $.ajax({
                url: '<?php echo UPDATE_PAY_USER_NAME ?>',
                method: 'POST',
                dataType: 'json',
                data: {trade_no: '<?php echo $_GET['trade_no'];?>', pay_username: pay_name,key : '<?php echo $user;?>'},
                success: function (data) {
                    if (data.code != 1) {
                        layer.msg(data.msg, {icon: 2, time: 1500})
                        return false;
                    }
                    layer.msg('提交成功', {icon: 1, time: 1500}, function () {
                        $("#dialog_tips").hide()
                    })
                }
            })

        }
    })
</script>

<script>

    //jump();
    var timeLimit = 0;

    function jump() {
        var is_show_time = false;

        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn: '确定',
            shadeClose:false,
            yes: function(index, layero){
                // charge_tips();
                if (is_show_time){
                    if (timeLimit == 5)
                    {
                        layer.close(index)
                    }
                } else {
                    var reg = /^[\u4E00-\u9FA3]{1,}$/;
                    var txt_pay_name = $.trim($('#txt_pay_name','#layui-m-layer0').val());
                    // if (txt_pay_name == '' || !reg.test(txt_pay_name)){
                    if (txt_pay_name == ''){
                        alert( "请正确输入支付宝昵称");
                        return false;
                    } else {
                        save_payname(txt_pay_name,index);
                    }
                }
            },
        })

    }

    jump();






    $('#btn_send_jump').on('click',function(){
//        window.location.href = 'https://credit.zmxy.com.cn/p/yuyan/180020010001232973/index.html?target=wa&amp;t=Sv1PY2toiyo7175'.replace(/&amp;/g,'&');
        window.location.href =  'alipays://platformapi/startapp?saId=10000007&qrcode=' + encodeURIComponent("<?php echo $gourl; ?>".replace(/&amp;/g,'&'));
        return false;
    });

</script>


</body></html>
