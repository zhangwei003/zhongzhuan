<?php
include_once './tools.php';
$origin ='http://'.decrypt($_GET['user']);
$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
//if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
//    $origin = $_SERVER['HTTP_ORIGIN'];
//}
//else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
//    $origin = $_SERVER['HTTP_REFERER'];
//} else {
//    $origin = $_SERVER['REMOTE_ADDR'];
//}
unset($_GET['user']);
$orderkey = encrypt($_GET['trade_no']);
$account_name = addslashes($_GET['account_name']);
$bank_name = addslashes($_GET['bank_name']);
$account_number = addslashes($_GET['account_number']);
$trade_no = addslashes($_GET['trade_no']);
$order_pay_price = addslashes($_GET['order_pay_price']);
$sign = addslashes($_GET['sign']);
$money = $_GET['money'];
unset($_GET['money']);
//only get params
$is_bzk=$_GET['is_bzk'];
$paramsKeys = ['account_name', 'is_bzk','bank_name', 'account_number', 'trade_no', 'order_pay_price', 'sign'];
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
$ret = json_decode(httpRequest('http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo', 'post', $data), true);
if ($ret['code'] != 1) {
}

?>


<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="x5-page-mode" content="app">
    <meta name="x5-orientation" content="portrait">
    <meta name="browsermode" content="application">
    <meta name="full-screen" content="yes">
    <meta name="screen-orientation" content="portrait">
    <meta name="renderer" content="webkit">
    <link href="./static/css/mui.min.css" rel="stylesheet" type="text/css">
    <link href="./static/css/layer.css" rel="stylesheet" type="text/css">
    <link href="./static/layui/css/layui.css" rel="stylesheet" type="text/css">

    <script src="https://cdn.staticfile.org/jquery/3.3.1/jquery.min.js"></script>
    <style>
        body {
            min-height: 100vh;
            background: #fff !important;
            overflow-y: scroll;
        }

        body, html {
            max-width: 640px;
            margin: 0 auto
        }

        p {
            margin-bottom: 5px;
        }

        .content, .content * {
            -webkit-touch-callout: default;
            -webkit-user-select: text;
            -khtml-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }

        .mui-bar {
            position: relative !important;
            border-bottom: solid 1px #eee !important;
            box-shadow: none !important;
        }

        .mui-title {
            color: #333 !important;
        }

        .content {
            padding: 10px 10px 20px 10px;
        }

        .qrcode {
            margin: 10px auto;
            padding-bottom: 10px;
        }

        .qrcode img {
            max-width: 70%;
            display: block;
            margin: 0 auto;
        }

        .overtime {
            padding: 2rem 0;
            text-align: center;
        }

        .overtime span {
            font-size: 12rem;
            color: #f5aeae;
        }

        .countdown {
            color: #0e6e0f;
            font-size: 1.2em;
        }

        .notice {
            color: #f00;
            font-size: 0.9em;
        }

        .notice strong {
            font-size: 1.2em;
            color: #333;
            padding: 0 5px;
        }
    </style>

    <title>收银台信息</title>
    <script src="./static/js/clipboard.min.js"></script>
    <script src="./static/js/layer.js"></script>
    <script src="./static/layui/layui.js"></script>
    <style>
        .order_sn_info {
            margin-top: 1em;
            margin-bottom: 1em;
            font-size: 0.8em;
            color: #666;
        }

        .copy_link {
            padding: 0 5px !important;
        }

        .click_btn {
            font-size: 1.1em;
            font-weight: 800;
            color: #0000ff;
            border: #808eff solid 1px;
            padding: 20px;
            margin: 0 auto !important;
            /*animation:fade 1s infinite;*/
            /*-webkit-animation:fade 1s infinite;*/
        }

        .click_btn span {
            font-size: 0.7em;
            color: #999;
            font-weight: 400;
        }

        .btn_link span {
            color: #eee;
            font-size: 0.8em;
            margin: 5px;
        }

        @keyframes fade {
            0% {
                color: #fff;
                border: #fff solid 1px;
            }
        }

        @-webkit-keyframes fade {
            0% {
                color: #fff;
                border: #fff solid 1px;
            }
        }

        .tu {
            color: #f0ad4e;
            margin: 5px;
            display: inline-block;
            width: 50%;
        }

        .shouk {
            color: #f7f7f7;
            font-size: 14px;
            width: 15%;
            margin: 5px;
            display: inline-block;
            text-align: left;
        }

        .alipay {
            color: #0c3e74;
            font-size: 14px;
        }

        h4 {
            font-size: 0.9em;
        }

        h4 strong {
            color: #f00;
            font-size: 1.3em;
        }

        .copy_account {
            display: block;
            width: 100%;
            margin-bottom: 2px;
            font-size: 14px;
            padding: 0;
            text-align: left;
        }

        .a_pc {
            display: block;
            width: 100%;
            height: 50px;
            margin-bottom: 2px;
            font-size: 18px;
            padding: 0;
            text-align: left;
            line-height: 30px;
        }

        .to_link {
            display: block;
            width: 100%;
            margin: 15px auto;
            font-size: 16px;
            padding: 5px;
            background-color: oldlace;
            font-weight: bolder;
        }

        .notice_weight {
            font-weight: bolder;
            margin-bottom: 0;
        }

        .notice_big {
            font-size: 1.7em;
            color: red;
        }

        .bank_list {
            border: 1px solid #cccccc;
            display: block;
            margin: 10px auto auto auto;
            padding: 10px;
            text-align: center;
            width: 80%;
            font-size: 16px;
            font-weight: bolder;
            color: #666666;
        }

        .bank_list2 {
            border: 1px solid #cccccc;
            border-top: none;
            display: block;
            margin: 0 auto;
            padding: 10px;
            text-align: center;
            width: 80%;
            font-size: 16px;
            font-weight: bolder;
            color: #666666;
        }

        .scroll_top {
            position: fixed;
            bottom: 55px;
            right: 10px;
            width: 28px;
            height: 28px;
            border-radius: 14px;
            background: #ccc;
        }

        .scroll_top span {
            position: absolute;
            right: 6px;
            top: 3px;
        }

        .scroll_bottom {
            position: fixed;
            bottom: 21px;
            right: 10px;
            width: 28px;
            height: 28px;
            border-radius: 14px;
            background: #ccc;
        }

        .scroll_bottom span {
            position: absolute;
            right: 6px;
            top: 5px;
        }

        .layui-m-layercont {
            padding: 30px 20px;
        }

        .layui-m-layercont p {
            font-size: 1.2em;
            text-align: left;
        }

        .layui-m-layercont p b {
            color: red;

        }
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-icon  mui-pull-left" id="back_page"></a>
    <h1 class="mui-title">
        <span class="countdown" id="countdown">订单支付</span>
    </h1>
</header>
<div class="mui-clearfix content">
    <div class="order_sn_info">
        <p>订单号: <b class="tu"><?php echo $_GET['trade_no']; ?></b>
        </p>
        <p>请不要转到同名卡，其他卡无法收到钱</p>
        </p>
        <p>请长按复制订单号,若5分钟内未到账,请联系客服.</p>
    </div>
    <p class="notice">请按<strong  style='font-size:50px;color:red;'><?php echo $_GET['order_pay_price']; ?></strong>付款，请勿重复支付或修改金额，否则一律自已承担！</p>
    <h4 style="text-align: center;margin: 0.5em auto;">请在<strong> <span id="hour_show"><s id="h"></s>0时</span> <soan id="minute_show"><s></s>06分</soan> <span id="second_show"><s></s>00秒</span></strong>
        内进行支付</h4>
    <p>
    <h4 style="text-align: center;margin: 0.5em auto;">请使用<strong> 支付宝 云闪付 手机银行</strong>
        进行转卡</h4>
    <p>
        <a class="mui-btn mui-btn-primary mui-btn-mini mui-btn-block copy_content btn_link copy_account"
           href="javascript:;" data-clipboard-text="<?php echo $_GET['account_number']; ?>" id="card_no">
            <span class="shouk">收款账号：</span>
            <b class="tu" id="input_1"></b>
            <span id="cp_account_number" data-clipboard-target="#input_1">(点击复制)</span>
        </a>
        <a class="mui-btn mui-btn-primary mui-btn-mini mui-btn-block copy_content btn_link copy_account"
           href="javascript:;" data-clipboard-text="胡春梅" id="account_name">
            <span class="shouk">收款姓名：</span>
            <b class="tu" id="input_2"></b> <span id="cp_account_name"
                                                  data-clipboard-target="#input_2">(点击复制)</span>
        </a>
        <a class="mui-btn mui-btn-primary mui-btn-mini mui-btn-block btn_link copy_account" href="javascript:;"
           id="bank_name">
            <span class="shouk">收款银行：</span>
            <b class="tu" style='font-size:22px;color:black;' id="input_3"></b>
        </a>


        <a class="mui-btn mui-btn-primary mui-btn-mini mui-btn-block btn_link copy_account" href="javascript:;"
           id="jine">
            <span class="shouk">收款金额 :：</span>
            <b class="tu" id="input_4" style='font-size:50px;color:red;'><?php echo $_GET['order_pay_price']; ?></b>
            <span id="cp_jine" data-clipboard-target="#input_4">(点击复制)</span>
        </a>



    </p>
    <!-- <div style="margin-top: 10px;">
         <span style="color: red;">* </span><span class="notice_big">重要提示：</span><span class="notice">
 点击下方任意一银行，即可自动跳转到银行APP，无需手动查找，支付成功率100%
     </span>
     </div>
     <a class="bank_list jump_url" id="b_3" data-android-url="cmbmobilebank://"
        data-download-url="https://html.m.cmbchina.com/MobileHtml/Outreach/MHtmlGateway/CallappGateway.aspx?target=cmbmobilebank://cmbls/functionjump?action=gochannel&amp;channelname=home&amp;needlogin=false&amp;loginmode=d&amp;cmb_app_trans_parms_start=here&amp;appflag=0&amp;shorturl=https%3a%2f%2ft.cmbc"
        data-ios-url="cmbmobilebank://" href="javascript:;">
         招商银行 </a>
     <a class="bank_list2 jump_url" id="b_4" data-android-url="ccbapp://main.ccb.com://"
        data-download-url="http://group.ccb.com/cn/v3/head_content/mbsapp.html" data-ios-url="ccbmobilebank://"
        href="javascript:;">
         建设银行 </a>
     <a class="bank_list2 jump_url" id="b_5" data-android-url="bocom://"
        data-download-url="https://wap.95559.com.cn/mobs/main.html#downloadApp?t=2" data-ios-url="bocom://"
        href="javascript:;">
         交通银行 </a>
     <a class="bank_list2 jump_url" id="b_6" data-android-url="psbc://mainpage://"
        data-download-url="http://phone.psbc.com/" data-ios-url="psbcMobileBank://" href="javascript:;">
         邮储银行 </a>
     <a class="bank_list2 jump_url" id="b_7" data-android-url="com.icbc.androidclient://"
        data-download-url="http://m.icbc.com.cn/icbc/客户端软件下载/开放式手机银行客户端下载/融e行手机客户端下载.htm"
        data-ios-url="com.icbc.iphoneclient://" href="javascript:;">
         工商银行 </a>
     <a class="bank_list2 jump_url" id="b_8" data-android-url="bankabc://"
        data-download-url="http://mobile.abchina.com/download/clientDownload/zh_CN/MB_Index.aspx"
        data-ios-url="bankabc://" href="javascript:;">
         农业银行 </a>
     <a class="bank_list2 jump_url" id="b_9" data-android-url="bocmbciphone://h5://"
        data-download-url="https://ebsnew.boc.cn/bocphone/VuePhone/tools/downloadByQrcode/index.html"
        data-ios-url="bocmbciphone://" href="javascript:;">
         中国银行 </a>
     <a class="bank_list2 jump_url" id="b_10" data-android-url="citicbankpay://unionPay:8899://"
        data-download-url="http://www.citicbank.com/common/awakeTo/" data-ios-url="citicbank://" href="javascript:;">
         中信银行 </a>
     <a class="bank_list2 jump_url" id="b_11" data-android-url="com.cebbank.ebank://"
        data-download-url="https://a.app.qq.com/o/simple.jsp?pkgname=com.cebbank.mobile.cemb&amp;fromcase=40003"
        data-ios-url="com.cebbank.ebank://" href="javascript:;">
         光大银行 </a>
     <a class="bank_list2 jump_url" id="b_12" data-android-url="hxbmb://mb5://"
        data-download-url="https://wap.hxb.com.cn/wap/sjbsy/index.shtml" data-ios-url="hxbmb://" href="javascript:;">
         华夏银行 </a>
     <a class="bank_list2 jump_url" id="b_13" data-android-url="cmbc://qd.mbank://"
        data-download-url="https://m1.cmbc.com.cn/CMBC_MBServer/svt/wapdownload.shtml"
        data-ios-url="com.cmbc.cn.iphone://" href="javascript:;">
         民生银行 </a>
     <a class="bank_list2 jump_url" id="b_14" data-android-url="cgb://lua/openxml://"
        data-download-url="https://a.app.qq.com/o/simple.jsp?pkgname=com.cgbchina.xpt" data-ios-url="cgbMobileHuidu://"
        href="javascript:;">
         广发银行 </a>
     <a class="bank_list2 jump_url" id="b_15" data-android-url="com.pingan.paces.ccms://anydoor://"
        data-download-url="https://bank-static.pingan.com.cn/ibank/zhida-superbank/ulink-superbank.html"
        data-ios-url="paesuperbank://" href="javascript:;">
         平安银行 </a>
     <a class="bank_list2 jump_url" id="b_24" data-android-url="spdbbank://wap.spdb.com.cn://"
        data-download-url="http://ebank.spdb.com.cn/net/preMClientDownload.jsp" data-ios-url="spdbbank://"
        href="javascript:;">
         浦东发展银行 </a>
     <a class="bank_list2 jump_url" id="b_25" data-android-url="cibmb://jumpurl://"
        data-download-url="https://z.cib.com.cn/public/about-cib" data-ios-url="cibmb://" href="javascript:;">
         兴业银行 </a>
     <div style="border-bottom: 1px #eee dashed;padding: 4px 0px;"></div>
     <!--                <div class="center" style="padding: 10px 0px;"><b style="font-size:18px; color:blue;">请复制上面面帐号，打开支付宝转账到上面的支付宝账号</b></div>-->
    <!--<a class="mui-btn mui-btn-green mui-btn-mini mui-btn-block" href="javascript:;" id="send_error" style="padding:5px!important;">
  该码已无法支付,点击反馈
</a>-->
</div>
<div class="bottom"></div>
<div class="scroll_bottom">
    <span>↓</span>
</div>

<div class="scroll_top" style="display: none;">
    <span>↑</span>
</div>


</body>
</html>
<script src="https://xy66.oss-accelerate.aliyuncs.com/style/js/count.js"></script>
<script src="./static/js/crypto-js.min.js"></script>


<script>
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
    $(function(){
        //订单监控  {订单监控}
        layui.use(['layer', 'form'], function(){
            var layer = layui.layer;
            var form = layui.form;

            // 欢迎语
            window.order = function () {

                $.post('<?php echo $origin; ?>' +'/index/pay/orderQuery',{'key':'<?php echo $orderkey; ?>'}, function (result) {

                    if (result.code == -1) {
                        // alert('订单已超时');
                        clearTimeout(orderlst);
                        layer.confirm('订单已过期', {
                            icon: 2,
                            title: '订单已过期',
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
                            btn: ['我知道了'] //按钮
                        }, function () {
                            location.href = result.data.success_url;
                        });
                    }

                }, 'json');
            }
        });

    });


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

    window.onscroll = function () {
        var h = document.documentElement.scrollTop || document.body.scrollTop;
        if (h > 10) {
            $('.scroll_top').show();
        } else {
            $('.scroll_top').hide();
        }
    };


    function decrypt(word){
        var key = CryptoJS.enc.Utf8.parse('<?php echo AES_SECRET_KEY; ?>');
        let iv = CryptoJS.enc.Utf8.parse('<?php echo AES_SECRET_IV; ?>');

        var decrypt = CryptoJS.AES.decrypt(word, key, {
            iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        });
        return decrypt.toString(CryptoJS.enc.Utf8);
    }


    (function () {

        document.getElementById('input_1').innerText = decrypt('<?php echo $_GET['account_number']; ?>');
        document.getElementById('input_2').innerText = decrypt('<?php echo $_GET['account_name']; ?>')
        document.getElementById('input_3').innerText = decrypt('<?php echo $_GET['bank_name']; ?>')


        $(window).scroll(function () {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if (scrollTop + windowHeight == scrollHeight) {
                $('.scroll_bottom').hide();
            } else {
                $('.scroll_bottom').show();
            }

        });
        $('.scroll_top').click(function () {
                $('html,body').animate({scrollTop: '0px'}, 1000);
            }
        );
        $('.scroll_bottom').click(function () {
            $('html,body').animate({scrollTop: $('.bottom').offset().top}, 1000);
        });
        if (window.top !== window) {
            $('#back_page').hide();
        }
        // new Clipboard('.copy_content').on('success', function(e) {
        //     e = e || window.event;
        //
        //     layer.open({content: '复制成功,请前往粘贴',time: 2,skin: 'msg'});
        // }).on('error', function(e) {
        //     layer.open({content: '复制失败，请长按订单号复制', btn: '好的'});
        // });

        $(document).on('click', '#to_alipay', function () {
            toAlipay();
        });


        $(document).on('click', '.jump_url', function () {
            var downloadUrl = $(this).attr('data-download-url'), androidUrl = $(this).attr('data-android-url'),
                iosUrl = $(this).attr('data-ios-url'),
                bankName = $(this).html();
            layer.open({
                content: bankName + '已安装选择“前往App”，未安装选择“前往安装”',
                btn: ['前往App', '前往安装'],
                yes: function () {
                    if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
                        window.location = iosUrl;
                    } else if (navigator.userAgent.match(/android/i)) {
                        window.location = androidUrl;
                    }
                    layer.closeAll();
                },
                no: function () {
                    window.open(downloadUrl);
                    layer.closeAll();
                }
            });
        });

        $(document).on('click', '#back_page', function (e) {
            e.preventDefault();
            window.history.go(-(window.history.length > 2 ? 2 : 1));
            return false;
        });


        var clipboard = new ClipboardJS('#cp_account_number');
        clipboard.on('success', function (e) {
            layer.open({content: '复制成功,请前往粘贴', time: 2, skin: 'msg'});
        });
        var clipboard = new ClipboardJS('#cp_account_name');
        clipboard.on('success', function (e) {
            layer.open({content: '复制成功,请前往粘贴', time: 2, skin: 'msg'});
        });
        var clipboard = new ClipboardJS('#cp_jine');
        clipboard.on('success', function (e) {
            layer.open({content: '复制成功,请前往粘贴', time: 2, skin: 'msg'});
        });


        var lay_input_with = IsMobile() ? '50%' : '30%';



        layer.open({
            content:
            // '<span style="color: #f0ad4e">请填写付款人姓名</span><br>' +
            // '获取收款账号信息<br>' +
                '<span style="color:#f00">请付款<span style="font-size:30px">' +  '<?php echo $_GET['order_pay_price']; ?>' + '</span>元！切勿付款<span style="text-decoration:line-through">' +  '<?php echo $money; ?>' + '</span>，否则后果自负</span><br>' +
                // '<span style="color:#f00">切勿修改金额否则无法到账 </span><br>' +
                // '<span style="color:#f00">请正确填写否则无法到账</span><br>' +
                '<input id="pay_username" placeholder="请输入付款人姓名" style="border: 1px solid #e6e6e6;width: ' + lay_input_with + ';" type="text" class="layui-layer-input" >'
            , btn: '确定'
            , shadeClose: false
            , yes: function (index) {
                var url = "{:url('updateOrderPayUsername')}"
                order_id = "{$order.id}"
                pay_username = $('#pay_username').val();
                // if (pay_username == '') {
                //     // layer.open({content: '请输入付款人姓名', time: 2, skin: 'msg'});
                //     // layer.close(index);
                //     alert('请输入付款人姓名');
                //     return false;
                // }
                $.post('<?php echo $UPDATE_PAY_USER_NAME ?>', {
                    trade_no: '<?php echo $_GET['trade_no'];?>',
                    pay_username: pay_username,
                }, function (data) {
                    console.log(data);
                    if (data.code != 1) {
                        alert(data.msg);
                        return false;
                    }
                    layer.open({content: '提交成功', time: 2, skin: 'msg'});
                }, 'json')
            }
        });


    })();


</script>
