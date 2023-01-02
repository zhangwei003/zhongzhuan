<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
unset($_GET['remark']);
unset($_GET['user']);
$account_name = addslashes($_GET['account_name']);
$bank_name = addslashes($_GET['bank_name']);
$account_number = addslashes($_GET['account_number']);
$trade_no = addslashes($_GET['trade_no']);
$order_pay_price = addslashes($_GET['order_pay_price']);
$sign = addslashes($_GET['sign']);
$gourl = decrypt($account_number);

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
<!-- saved from url=(0114)http://pay5.wuhengshop.com/c/api/pay?osn=2023010218585113124382251&t=1672657131&k=8d5ea0c0a9868330114a780e02243a22 -->
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
        #layui-m-layer1 .layui-m-layercont {
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
<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">

                <div style="text-align: center;">
                    <img src="./static/img/rmb_logo.jpg" style="width: 20%;" alt="">
                </div>
                <div class="text-center">
                    <p class="" style="margin-top: 10px;font-size: 16px;">
                        <?php echo $trade_no; ?>                    </p>
                </div>
<!--                <div class="text-center">-->
<!--                    <p class="" style="margin-top: 18px;font-size: 16px;font-weight: 900">-->
<!--                        支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">04分21秒</span>-->
<!--                    </p>-->
<!--                </div>-->
                <div class="text-left" style=" align-items: center;font-size:15px;line-height:35px;height:30px;color:#000;">
                    <div style="display: flex;">
                        <span style="width: 30%">金额</span>
                        <span style="width: 60%" id="cp_order_money"><?php echo $order_pay_price; ?></span>
                        <span style="width: 20%">
                      <button type="button" class="btn btn-info btn-xs copy0" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-text="<?php echo $order_pay_price; ?>" onclick="copy_txt('copy0')">复制</button>
<!--                            <span class="copy_btn copy0" style="background: #000;color: #fff;    padding: 3px;    margin-left: 5px;    border-radius: 2px;" data-clipboard-text="--><?php //echo $_GET['trade_no']; ?><!--" onclick="copy_txt('copy0')">复制</span>-->
                    </span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 30%">姓名</span>
                        <span style="width: 60%" id="cp_name"><?php echo decrypt($bank_name); ?></span>
                        <span style="width: 20%">
                             <button type="button" class="btn btn-info btn-xs copy1" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-text="<?php echo decrypt($bank_name); ?>" onclick="copy_txt('copy1')">复制</button>
                    </span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 30%">钱包编号</span>
                        <span style="width: 60%" id="cp_account_no"><?php echo decrypt($account_number); ?></span>
                        <span style="width: 20%;">
                      <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-text="<?php echo decrypt($account_number); ?>" onclick="copy_txt('copy2')">复制</button>
                    </span>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <p style="margin-top:45px"></p>
                <div style="font-size:18px;font-weight:900;color:red;margin-top:12px;">
                    充值教程：
                </div>

                <div style="font-size:14px;color:red;margin-top:5px;margin-top:10px;font-weight: bold;">
                    <p>1.在手机应用商店下载"数字人民币"APP。</p>
                    <p>2.进入数字人民币首页，点击【转钱】，选择【钱包编号】，复制粘贴钱包编号，
                        点击【确定】，填写支付金额，输入收款人姓名，完成支付即可上分。
                    </p>
                    <p>3.订单一次性有效，请勿重复付款/超时付款/付错金额，否则不到账不退补，损失由您自已承担！</p>
                </div>
                <br>
                <div style="text-align: center;">
                    <img src="./static/img/rmb_tips.jpg" style="width: 98%;" alt="">
                </div>
            </div>
        </div>
    </div></section>

<div id="dialog_tips2" style="display:none;">
    <p style="text-align: center;color:red;font-size: 16px;font-weight: bold;">  注意！注意！注意！</p>
    <p style="text-align: center;color:#0b0b0b;  font-size: 16px;font-weight: bold;">本单只需支付 <span style="color:red"> <?php echo $order_pay_price; ?> </span> 元  </p>
    <p style="text-align: center;color:#0b0b0b;  font-size: 16px;font-weight: bold;">请务必按此金额支付</p>
    <p style="text-align: center;color:red;  font-size: 16px;font-weight: bold;">超时支付 / 重复支付 / 付错金额导致的不到账，损失自已承担</p>
</div>

<div id="dialog_tips" style="display:none;">
    <span style="font-size: 2rem;font-weight: bold;">
   <p style="text-align: center;font-size: 1.6rem;color:red;line-height: 3rem;">请正确填写付款钱包的昵称</p>
   <p style="text-align: center;color:red;font-size: 1.6rem;"> 输入错误将无法到账，切记切记！</p>
   <p style="text-align: center;color:#000000;font-size: 1.6rem;line-height: 3rem;">
       <input type="text" placeholder="请输入付款钱包昵称" id="txt_pay_name" value="" style="text-align:center; width: 220px ;border: 1px solid #bcb4b4;">
   </p>
    </span>

</div>

<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/xss.js"></script>
<script>



    function copy_txt(id) {
        var clipboard = new ClipboardJS('.'+id);
        clipboard.on('success', function(e) {
            layer.open({
                content: "复制成功",
                skin: "msg",
                time: 1,
            });
            // e.clearSelection();
        });
        clipboard.on('error', function(e) {
            layer.open({
                content: "复制失败",
                skin: "msg",
                time: 1,
            });
        });
    }

</script>
<script>
    var timeLimit = 0;
    function jump(dialog_index) {
        var index = layer.open({
            content: $('#dialog_tips2').html(),
            btn:"知道了",
            shadeClose:false,
            yes: function(index, layero){
                if (timeLimit == 5) {
                    layer.close(index);
                }
            },
        })

        setTimeout(function(){
            setTimer();
        },100);

        var timer = null;
        var d_time = 5;
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
            var d_time_flag = secTrans(5);

            $('#layui-m-layer' + dialog_index).find('.layui-m-layerbtn').find('span').html('知道了(' + d_time_flag + ')');
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
                $('#layui-m-layer' + dialog_index).find('.layui-m-layerbtn').find('span').html('知道了' + d_time_flag );
            },1000);
        }

        setTimeout(function(){
            timeLimit = 5;
        }, 5000);
    }

    // jump();
    var show_dialog = "0";
    function jump2() {
        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn: '确定',
            shadeClose:false,
            yes: function(index, layero){
                var reg = /^[\u4E00-\u9FA3]{1,}$/;
                var txt_pay_name = $.trim($('#txt_pay_name','#layui-m-layer0').val());
                if (txt_pay_name == ''){
                    alert( "请正确输入付款钱包的昵称");
                    return false;
                } else {
                    save_payname(txt_pay_name,index);
                }

            },
        })
    }
    if (show_dialog > 0) {
        jump2();
    } else{
        jump(0);
    }

</script>





</body></html>