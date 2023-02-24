<?php
include_once './tools.php';
$user = $_GET['user'];
$orderId = 20000000 + $_GET['remark'];
$is_pay_name = $_GET['is_pay_name'];
unset($_GET['is_pay_name']);
unset($_GET['remark']);
unset($_GET['user']);
$account_name = addslashes($_GET['account_name']);
$bank_name = addslashes($_GET['bank_name']);
$account_number = addslashes($_GET['account_number']);
$trade_no = addslashes($_GET['trade_no']);
$order_pay_price = addslashes($_GET['order_pay_price']);
$sign = addslashes($_GET['sign']);
$url = 'https://www.alipay.com/?appId=20000123&actionType=scan&biz_data={"s":"money","u":"'.decrypt($account_number).'","a":"'.$order_pay_price.'","m":"莲花清温'.$orderId.'"}';
$gourl = 'alipays://platformapi/startapp?appId=68687093&url='.urlencode($url);

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
                    <img src="./static/img/alipay.png" style="width: 40%;" alt="">
                </div>
                <div class="text-center" style="font-size:16px;line-height:30px;height:40px;color:#000;margin:20px 0;">
                    <span id="ht_order_no">订单号：<?php echo $_GET['trade_no']; ?></span>
                </div>
                <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:20px 0 20px 0">
                    ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                </div>
                <div class="text-center">
                    <p class="time" style="margin-top: 20px;font-size: 16px;font-weight: 900">
                       <span id="time" class="text-danger" style="opacity: 1;">请在5分钟内支付</span>
                    </p>
                </div>
                <div class="text-center">
                    <span style="color:#ff0000;font-size:1.5rem;font-weight: bold;">请在规定时间内完成支付，超时失效请勿支付 <br></span>
                    <span style="color:#ff0000;font-size: 1.5rem;font-weight: bold;">此码只能支付一次,重复支付无法到账不退还  <br></span>
                    <span style="color:#ff0000;font-size: 1.5rem;font-weight: bold;">出现安全提示请忽略，正常支付，秒上分<br></span>
                    <span style="color:#ff0000;font-size: 1.5rem;font-weight: bold;">支付遇到风险提示，点击“确定”再次支付即可 <br></span>

                </div>
                <br>
                <div class="kltips"></div>

                <div class="text-center">
                    <div id="btn_send" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 5rem;line-height: 5rem;width: 100%;margin: 0 auto;border-radius: 8px">
                        点击启动支付宝付款
                    </div>
                    <br>
                    <div class="text-center">
                        <span style="color:#ddc03f;font-size:1.5rem;font-weight: bold;"> 推荐使用截图扫码成功率更高</span>
                    </div>
                    <div class="text-center qrcode_img">

                    </div>
                    <div class="text-center" style="font-weight: bold;font-size:16px;line-height:20px;height:40px;margin:15px 0;">
                        <p>PC用户请使用手机支付宝扫描二维码完成支付</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="dialog_tips" style="display:none;">
    <span style="font-size: 2rem;font-weight: bold;">
   <p style="text-align: center;font-size: 1.8rem;color:#000000;line-height: 3rem;"> 请输入付款人姓名 </p>
   <p style="text-align: center;color:red;font-size: 1.8rem;"> 请正确填写付款支付宝号的真实姓名</p>
   <p style="text-align: center;color:#000000;font-size: 1.6rem;line-height: 3rem;">
       <input type="text" placeholder="请输入付款人姓名" id="pay_name" name="pay_name" value="" style="text-align:center; border: 1px solid #bcb4b4;">
   </p>
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
                    var txt_pay_name = $.trim($('#pay_name','#layui-m-layer0').val());
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

    function  save_payname(pay_name,index){
        $.post('<?php echo UPDATE_PAY_USER_NAME ?>', {trade_no: '<?php echo $_GET['trade_no'];?>', pay_username: pay_name,key : '<?php echo $user;?>'}, function (e) {
            if (e.code != 1) {
                alert(e.msg)
                return false;
            } else {
                // alert( e.msg);
                layer.close(index)
                // jump2(1)
                return true;
            }
        }, "json");
        return false;
    }


    <?php if ($is_pay_name == 1){ ?>
    jump();
    <?php } ?>
</script>

</body>
</html>
