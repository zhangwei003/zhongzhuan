<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
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

<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>支付宝扫码</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            width: 100%;
            height: 100%;
            font-size: 14px;
            color: #333;
            background: #00aaee;
            font-family: "microsoft yahei";
        }

        p {
            margin: 5px auto;
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        .dialog-wrap,
        .pay-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .dialog-wrap,
        .pay-wrap {
            display: none;
        }

        .dialog-wrap {
            width: 280px;
        }

        .dialog {
            border-radius: 10px;
            border: solid #00aaee 1px;
            text-align: center;
            line-height: 1.6;
            background: #fff;
        }
        .title {
            font-size: 20px;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .content {
            text-align: left;
            padding: 10px;
        }
        .contentp {
            padding: 3px;
            margin: 5px auto;
            text-align: left;
        }

        .sub-title {
            font-size: 13px;
            color: #999;
        }


        button {
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 3px;
            margin: 5px auto;
            background: #00aaee;
            margin-bottom: 20px;
        }

        .agree {
            background: #00aaee;
        }

        .disagree {
            background: #ccc;
            margin-bottom: 20px;
        }

        .pay-wrap {
            width: 100%;
            height: 100%;
            /* overflow: hidden; */
            overflow-y: hidden;
        }

        .pay-box {
            width: 320px;
            background: #fff;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 15px;
            box-sizing: border-box;
            max-height: 100%;
            overflow-y: auto;
        }

        .top {
            width: 100%;
            padding-top: 5px;
            overflow: hidden;
        }

        .pay-item {
            overflow: hidden;
        }

        .order-num {
            font-size: 15px;
            text-align: center;
        }

        .amount {
            font-size: 30px;
            font-weight: bolder;
            text-align: center;
            margin-top: 5px;
            vertical-align: top;
            color: red;
        }

        .amount:before {
            content: '';
            font-size: 18px;
        }

        .qrcode-box {
            width: 160px;
            height: 160px;
            /* border: 1px solid #ccc; */
            margin: 5px auto;
            position: relative;
        }

        .qrcode-box canvas,
        .qrcode-box img {
            width: 100%;
            height: 100%;
        }

        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate3d(-50%, -50%, 0);
        }

        .loading p {
            font-size: 12px;
            text-align: center;
            margin-top: 15px;
        }

        .timer {
            font-size: 23px;
            color: red;
        }

        .tips {
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding: 15px 10px;
            background: #FBFBFB;
        }

        strong {
            color: red;
        }

        .open-webchat-a {
            display: none;
        }

        .open-wechat {
            display: block;
            margin: 0 auto 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .ad-wrap {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            color: #fff;
            text-shadow: 0px 0px 2px #000;
        }

        @media screen and (orientation: landscape) {

            .dialog-wrap,
            .pay-box {
                width: 95%;
            }

            .pay-box {
                position: relative;
                transform: none;
                top: 0;
                left: 0;
                margin: 10px auto;
                height: 95%;
                overflow: hidden;
                padding: 0;
            }

            .top {
                width: 50%;
                box-sizing: border-box;
                float: left;
            }

            .bottom {
                width: 49.5%;
                box-sizing: border-box;
                float: left;
                height: 100%;
            }

            .tips {
                height: 100%;
            }

            .qrcode-box {
                width: 120px;
                height: 120px;
            }
        }

        #loading3 {
            position: relative;
            width: 50px;
            height: 50px;
        }

        .demo3 {
            width: 4px;
            height: 4px;
            border-radius: 2px;
            background: #00aaee;
            position: absolute;
            animation: demo3 linear 0.8s infinite;
            -webkit-animation: demo3 linear 0.8s infinite;
        }

        .demo3:nth-child(1) {
            left: 24px;
            top: 2px;
            animation-delay: 0s;
        }

        .demo3:nth-child(2) {
            left: 40px;
            top: 8px;
            animation-delay: 0.1s;
        }

        .demo3:nth-child(3) {
            left: 47px;
            top: 24px;
            animation-delay: 0.1s;
        }

        .demo3:nth-child(4) {
            left: 40px;
            top: 40px;
            animation-delay: 0.2s;
        }

        .demo3:nth-child(5) {
            left: 24px;
            top: 47px;
            animation-delay: 0.4s;
        }

        .demo3:nth-child(6) {
            left: 8px;
            top: 40px;
            animation-delay: 0.5s;
        }

        .demo3:nth-child(7) {
            left: 2px;
            top: 24px;
            animation-delay: 0.6s;
        }

        .demo3:nth-child(8) {
            left: 8px;
            top: 8px;
            animation-delay: 0.7s;
        }

        @keyframes demo3 {

            0%,
            40%,
            100% {
                transform: scale(1);
            }

            20% {
                transform: scale(3);
            }
        }

        @-webkit-keyframes demo3 {

            0%,
            40%,
            100% {
                transform: scale(1);
            }

            20% {
                transform: scale(3);
            }
        }
    </style>
</head>
<body>
<div class="pay-wrap" id="payWrap">
    <div class="pay-box">
        <div class="top">
            <p>支付宝扫码</p>
            <div class="amount">
                <span class="value" id="amount">￥<?php echo $_GET['order_pay_price']; ?>元</span>
            </div>
            <p>其他金额不到帐不退款</p>
            <div class="pay-item">
                <div class="qrcode-box" id="qrcode">
                    <div class="loading" id="loading">
                        <div id="loading3">
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                            <div class="demo3"></div>
                        </div>
                        <p>加载中</p>
                    </div>
                </div>
            </div>
<!--            <p><button class="agree" onclick="pay()" >点 我 立 即 支 付</button></p>-->
            <p> 验证姓名 【<strong id="realName"><?php echo decrypt($_GET['account_name']); ?></strong>】 </p>
            <p> <span class="value" id="orderId"><?php echo $_GET['trade_no']; ?></span> </p>
        </div>
        <div class="bottom">
            <p>截屏 启动支付宝 扫一扫 按下单金额支付</p>
            <p>不要重复支付 不要修改金额 不要超时支付</p>
        </div>
    </div>
</div>
</body>
<script src="./static/js/jquery.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<!--
<script src="/static/pay/qrcode.min.js?v=20220914193400001"></script>
-->
<script>

    $('#btn').click(function(){
        $('.layer-tips').hide();
    });

    var url = '<?php echo $gourl; ?>';



    //生成二维码
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


    $('#btn_send').on('click',function(){

        window.location.href = url;
        return false;
    });
</script>
</html>
