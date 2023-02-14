<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_CardPwd= 'http://'.decrypt($_GET['user']).'/api/pay/saveCardPwd';
$is_pay_name = $_GET['is_pay_name'];
$origin ='http://'.decrypt($_GET['user']);
unset($_GET['is_pay_name']);
unset($_GET['remark']);
unset($_GET['user']);
$orderkey = encrypt($_GET['trade_no']);
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
            color: #027AFF;
            padding: 1rem;
            text-align: center
        }
        .center-sign .warmTips{color: #f58807;border-top: 1px solid #f2f2f2;margin-top: 1.2rem;padding: 0 4%;line-height: 1.7rem;}
        .center-sign .warmTips h3{padding: 1rem 0 0.5rem;}
        .center-sign .warmTips p{}
    </style>
    <title>支付</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/layui/css/layui.css" rel="stylesheet" />
</head>

<body style="">
<!-- start: page -->

<section class="body-sign mobile" style="position: relative;padding-top:0">
    <form id="post_from" class="layui-form">
        <div class="center-sign" style="padding-top:0">
            <div class="panel panel-sign">
                <div class="panel-body" style="min-height: 100vh">

                    <div class="text-center">
                        <img src="./static/img/alipay_logo.png" style="width: 20%;" alt="">
                    </div>
                    <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:20px 0 20px 0">
                        ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                    </div>
                    <div class="text-center" style="margin-bottom: 5%">
                        <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">
                            支付倒计时：<span id="time" class="text-danger" style="opacity: 1;"><span id="minute_show">00</span><span id="second_show">00秒</span></span>
                        </p>
                    </div>

                    <div style="text-align:center ;font-size:16px;color:red;margin-top:15px;font-weight: bold;">
                        <p>下单购买后，查看卡密</p>
                        <p>返回本页面填写卡密，提交充值</p>
                    </div>
                    <div class="text-center">

                        <input type="text" name="nums" lay-verify="required" placeholder="请输入购买的E卡卡密" style="height: 4rem;width:24rem; text-align:center; border: 1px solid #bcb4b4;border-radius: 2px"> <br>
                        <br>
                        <div id="btn_send" lay-submit="" lay-filter="postBtn" style="background: #1b76fc;color: #ffffff;font-size: 1.3rem;height: 4rem;line-height: 4rem;width: 90%;margin: 0 auto;border-radius: 8px">
                            点击提交充值
                        </div>

                        <br>
                        <div class="text-center qrcode_imgs">

                        </div>
                        <br>
                        <div class="text-center">
                            <div id="btn_jump" style="background: #027AFF;color: #ffffff;font-size: 1.3rem;height: 4rem;line-height: 4rem;width: 90%;margin: 0 auto;border-radius: 8px">
                                点击打开淘宝APP购买E卡
                            </div>
                        </div>
                    </div>
                    <div class="text-center" style="color:#027AFF;font-weight: bold;font-size:16px;line-height:20px;height:30px;margin:25px 0;">
                        <p>请确保手机已安装淘宝APP，否则无法支付</p>
                        <p>如果无法跳转到淘宝APP，请截屏保存二维码，打开淘宝APP扫一扫购买</p>
                    </div>
                    <br>
                    <div style="font-size:14px;font-weight: bold;color:#027AFF;rgin-top:5px;">
                        充值教程：
                    </div>

                    <div style="text-align:left ;font-size:14px;color:red;margin-top:5px;font-weight: bold;line-height: 18px;">
                        <p>1.点击跳转到淘宝APP(手机上需要安装淘宝APP)</p>
                        <p>2.点击立即购买</p>
                        <p>3.进入订单打开联系客服，查看卡密</p>
                        <p>4.复制卡密，返回到本页面，将卡密粘贴到上方输入框内，点击提交充值即可</p>
                    </div>
                    <br>

                </div>
            </div>
        </div>

    </form>
</section>
<section class="body-sign pc" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;">
                    <img src="./static/img/alipay_logo.png" style="width: 50%;" alt="">
                </div>
                <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:10px 0 20px 0">
                    ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>

                    <!-- <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
                </div>
                <!--            <div class="text-center">-->
                <!--                <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">-->
                <!--                    支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">02分38秒</span>-->
                <!--                </p>-->
                <!--            </div>-->

                <!--<div style="font-size:18px;font-weight:900;color:red;margin-top:15px;">
                    充值教程：
                </div>-->
                <br>
                <div class="text-center qrcode_img">
                    <!--                <img style="width:50%" src="./static/img/qrcode">-->
                </div>
                <div class="text-center" style="margin-top:10px;">
                    <span style="font-size:1.5rem;">PC用户请使用手机浏览器扫描二维码完成支付</span>

                </div>

                <div class="kltips"></div>


            </div>
        </div>
    </div>
</section>
<div id="dialog_tips" style="display:none;">
    <span style="text-align: center;font-size: 1.8rem;font-weight: bold;color: #423f3f;">

   <p style="text-align: center;color:red;"> ⚠️⚠️⚠️ 注意注意注意⚠️⚠️⚠️</p>
    <br>
            <p style="text-align: center;color:red;">严禁购买后一键绑卡</p>
    <p style="text-align: center;color:red;">自已绑定直接作废</p>
    </span>
</div>
<script src="./static/js/jquery.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<script src="./static/layui/layui.js"></script>

<script>
    function IsMobile() {
        var isMobile = {
            Android: function () {
                return navigator.userAgent.match(/Android/i) ? true : false;
            },
            BlackBerry: function () {
                return navigator.userAgent.match(/BlackBerry/i) ? true : false;
            },
            iOS: function () {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
            },
            Windows: function () {
                return navigator.userAgent.match(/IEMobile/i) ? true : false;
            },
            any: function () {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS()
                    || isMobile.Windows());
            }
        };

        return isMobile.any(); //是移动设备
    }

    var is_mobile = IsMobile() ? 1 : 0;

    if (is_mobile > 0) {
        jump();
        $(".pc").hide();
    }else{
        $(".mobile").hide();
    }



    var url = window.location.href;
    function getQrcode(url,qrcode_with=200,qrcode_height=200){
        $(".qrcode_img").qrcode({
            render: "canvas",
            width:200,
            height:200,
            text: decodeURIComponent(url)
        });
        $('#image').hide();
        $('.qrcode_img').find('canvas').css({'width':qrcode_with,'height':qrcode_height});
    }
    getQrcode(encodeURIComponent(url));





    var  order_s = "<?php echo $_GET['trade_no']; ?>";
    var pad = function(num){
        if (num < 10){
            num =  '0' + num;
        }
        return num;
    }


    function timer(intDiff) {
        var sTotal = parseInt(intDiff);
        window.setInterval(function () {
            var minute = 0, second = 0;//时间默认值
            if (sTotal > 0) {
                day = Math.floor(sTotal / (60 * 60 * 24));
                hour = Math.floor(sTotal / (60 * 60)) - (day * 24);
                minute = Math.floor(sTotal / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(sTotal) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#hour_show').html('<s id="h"></s>' + hour + '时');
            $('#minute_show').html('<s></s>' + minute + '分');
            $('#second_show').html('<s></s>' + second + '秒');
            sTotal--;
            if (sTotal < 1) {
                // document.getElementById("qrImg").src = timeout_img;
            }
        }, 1000);
    }

    var orderlst = setInterval("order()", 1000);
    var deadline_time = 0;
    var turl = 0;
    var is = false;
    layui.use(['util', 'laydate', 'layer','form'], function () {
        var form = layui.form;
        var layer = layui.layer;
        form.on('submit(postBtn)', function(data){
            var nums = $.trim($('input[name="nums"]').val());
            $.post("<?php echo $UPDATE_PAY_CardPwd; ?>", {"sn": "<?php echo $_GET['trade_no']; ?>","cardKey": nums}, function (e) {

                if (e.code == 0) {
                    layer.msg(e.msg,{icon:2,time:1500},function (){
                        $('input[name="nums"]').val('');
                    })

                } else {
                    layer.msg(e.msg,{icon:1,time:1500},function (){
                        window.location.reload();
                    })

                }
            }, "json");


            return false;
        });


        window.order = function () {

            $.post('<?php echo $origin; ?>' +'/index/pay/orderQuery',{'key':'<?php echo $orderkey; ?>'}, function (result) {

                if (result.code == -1) {
                    // alert('订单已超时');
                    clearTimeout(orderlst);
                    layer.confirm('订单已过期', {
                        icon: 2,
                        title: '订单已过期',
                        closeBtn:false,
                        shadeClose: false,
                        btn: ['我知道了'] //按钮
                    }, function () {
                        location.href = result.data.success_url;
                        return;
                    });

                }

                // $('.amount').html(result.data.amount);

                if (is == false) {
                    deadline_time = result.data.deadline_time;
                    timer(deadline_time);
                    is = true;

                }

                //成功
                if (result.code == 200) {
                    // document.getElementById("qrImg").src = success_img;
                    clearTimeout(orderlst);
                    layer.confirm('支付成功', {
                        icon: 1,
                        title: '支付成功',
                        closeBtn:false,
                        btn: ['我知道了'] //按钮
                    }, function () {
                        location.href = result.data.success_url;
                    });
                }

            }, 'json');
        }



        function charge_tips(msg) {
            var index = layer.open({
                content: msg,
                // btn: ['知道了'],
                shadeClose:false,
                yes: function(index, layero){

                }
            })
        }

    });

    var urls = '';
    <?php if($_GET['order_pay_price'] == '100.00'){ ?>
    urls = 'https://m.tb.cn/h.Un98HCE?tk=Vh9HdhtHZFV'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.Un98HCE?tk=Vh9HdhtHZFV';
        return false;
    });
    <?php  }elseif ($_GET['order_pay_price'] == '200.00'){ ?>
    urls = 'https://m.tb.cn/h.UMfQb1H?tk=adktdhtFgAi'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.UMfQb1H?tk=adktdhtFgAi';
        return false;
    });
    <?php } elseif ($_GET['order_pay_price'] == '300.00'){?>
    urls = 'https://m.tb.cn/h.Un9P4iI?tk=UkWRdhtFbBw'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.Un9P4iI?tk=UkWRdhtFbBw';
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1107846.html';
    <?php } elseif ($_GET['order_pay_price'] == '500.00'){?>
    urls = 'https://m.tb.cn/h.Un9PLLB?tk=Da3pdhtuklz'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.Un9PLLB?tk=Da3pdhtuklz';
        return false;
    });
    <?php } elseif ($_GET['order_pay_price'] == '1000.00'){?>
    urls = 'https://m.tb.cn/h.UMfSfqh?tk=nncYdhtsKxG'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.UMfSfqh?tk=nncYdhtsKxG';
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1107842.html'
    <?php } elseif ($_GET['order_pay_price'] == '2000.00'){?>
    urls = 'https://m.tb.cn/h.UMfjeqg?tk=ZYQbdhtuacG'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.UMfjeqg?tk=ZYQbdhtuacG';
        return false;
    });
    // urls = 'https://item.m.jd.com/product/3020581.html';
    <?php }else{ ?>
    urls = 'https://m.tb.cn/h.Un98HCE?tk=Vh9HdhtHZFV'
    $('#btn_jump').on('click',function(){
        window.location.href = 'taobao://m.tb.cn/h.Un98HCE?tk=Vh9HdhtHZFV';
        return false;
    });
   <?php } ?>

    //生成二维码
    function getQrcodes(url,qrcode_with=200,qrcode_height=200){
        $(".qrcode_imgs").qrcode({
            render: "canvas",
            width:200,
            height:200,
            text: decodeURIComponent(url)
        });
        $('#image').hide();
        $('.qrcode_imgs').find('canvas').css({'width':qrcode_with,'height':qrcode_height});
    }
    getQrcodes(encodeURIComponent(urls));



    var timeLimit = 0;
    function jump() {
        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn:"我已知晓",
            closeBtn:false,
            shadeClose:false,
            yes: function(index, layero){
                layer.close(index);
            },
        })
    }
</script>

</body></html>
