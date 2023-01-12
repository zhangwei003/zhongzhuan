<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
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

<html style="font-size: 48px;">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
    <title>第三方充值</title>
    <script>function rem(){
            var t=100,o=750,e=document.documentElement.clientWidth||window.innerWidth,n=Math.max(Math.min(e,480),320),h=50;
            320>=n&&(h=Math.floor(n/o*t*.99));
            n>320&&362>=n&&(h=Math.floor(n/o*t*1));
            n>362&&375>=n&&(h=Math.floor(n/o*t*1));
            n>375&&(h=Math.floor(n/o*t*.97));
            document.querySelector("html").style.fontSize=h+"px"
        }
        rem();
        window.onresize=function(){
            rem();
        }</script>
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/app.b101a6037a5d3c13c293c75cc9977c61.css" rel="stylesheet" />
    <link href="./static/layui/css/layui.css" rel="stylesheet" />
    <style id="tsbrowser_video_independent_player_style" type="text/css">
        [tsbrowser_force_max_size] {
            width: 100% !important;
            height: 100% !important;
            left: 0px !important;
            top: 0px !important;
            margin: 0px !important;
            padding: 0px !important;
        }
        [tsbrowser_force_fixed] {
            position: fixed !important;
            z-index: 9999 !important;
            background: black !important;
        }
        [tsbrowser_force_hidden] {
            opacity: 0 !important;
            z-index: 0 !important;
        }
        [tsbrowser_hide_scrollbar] {
            overflow: hidden !important;
        }

        .time-item{ display: block;margin: auto}

        #btn{
            cursor:pointer
        }

    </style>
</head>
<body>
<div id="app" class="view">
    <div data-v-48db887e="" class="ex-payRecharge">
        <section data-v-48db887e="" class="ex-payRechargeDetail-main">
            <div data-v-48db887e="" class="ex-pay">
                <div data-v-48db887e="" class="ex-pay-box" style="">
                    <div data-v-48db887e="" class="ex-pay-info">
                        <div data-v-48db887e="" class="ex-pay-logo">
                            <img data-v-48db887e="" src="./static/img/alipay.png" />
                        </div>
                        <div class="time-item" style="padding-top: 10px;color:red;padding: 25px;text-align: center;font-size: 17px">
                            <div class="time-item" id="msg2"><strong>此二维码不可多次扫码否则会出现无法到账</strong> </div>
                            <div class="time-item" id="msg2"><strong>请按页面提示金额付款<span class="amount-x"><?php echo $_GET['order_pay_price']; ?></span>元，否则不到账</strong> </div>
                            <div class="time-item" id="msg2"><strong>请在5分钟内及时付款，请勿超时付款</strong> </div>
                            <div class="time-item" id="msg2"><strong>如扫码出现风险异常请复制下面支付宝账号进行转账操作！</strong> </div>
                        </div>
<!--                        <div style="text-align: center">-->
<!--                            <p style="padding: 5%;padding-top: 0;">-->
<!--                                收款人：--><?php //echo decrypt($_GET['account_name']); ?>
<!--                                <br>-->
<!--                                收款账号：--><?php //echo decrypt($_GET['bank_name']); ?>
<!--                            </p>-->
<!--                        </div>-->
                        <div data-v-48db887e="" class="ex-pay-text" onclick="pay()">
                        <span data-v-48db887e="">点击启动支付</span>
                        </div>
                        <p data-v-48db887e="" class="ex-pay-yen">&yen;<?php echo $_GET['order_pay_price']; ?></p>
                        <div data-v-48db887e="" class="ex-pay-img">
                            <div class="qrcode_img" id="qrImg"></div>
                            <img data-v-48db887e="" src="./static/img/icon.ebb38a2.png" class="ex-pay-icon" />
                        </div>
                        <div data-v-48db887e="" class="ex-pay-warn">
                            <img data-v-48db887e="" src="./static/img/icon7.bc3e45d.png" alt="" />
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <?php if ($is_pay_name == 1){ ?>
        <div data-v-48db887e="" class="layer-tips" id="layer-tipss" style="">
            <div data-v-48db887e="" class="layer-tips-mask"></div>
            <div data-v-48db887e="" class="layer-tips-info tjname" style="text-align: center">
                <strong data-v-48db887e="" class="layer-tips-info-p" style="font-size: 15px">请输入付款人姓名</strong>
                <p data-v-48db887e="" class="layer-tips-info-p2" style="color: red;
                        width: 50%;
                        margin: auto;
                        font-size: 15px;">请正确填写付款支付宝号的真实姓名</p>

                <p>
                    <div class="layui-form">
                    <input type="text" class="layui-input" id="pay_name" name="pay_name" placeholder="请输入付款人姓名" value="" style="width: 50%;margin: auto;margin-top: 15px;">
                </div>
                </p>
                <button  id="btns" lay-submit lay-filter="formDemo" style="cursor: pointer" type="button">确定</button>
            </div>
        </div>
        <?php } ?>

        <div data-v-48db887e="" class="layer-tips" id="layer-tips" style="display: none" >
            <div data-v-48db887e="" class="layer-tips-mask"></div>
            <div data-v-48db887e="" class="layer-tips-info">
                <p data-v-48db887e="" class="layer-tips-info-p">请 <span data-v-48db887e="">扫码</span> 付款 <span data-v-48db887e=""><?php echo $_GET['order_pay_price']; ?></span> 元</p>
                <p data-v-48db887e="" class="layer-tips-info-p2">修改金额不到账不退补,请截屏后打开支付宝扫码</p>
                <button data-v-48db887e="" id="btn">我知道了</button>
            </div>
        </div>
    </div>
</div>
<div class="vux-alert">
    <div class="vux-x-dialog">
        <div class="weui-mask" style="display: none;"></div>
        <div class="weui-dialog" style="display: none;">
            <div class="weui-dialog__hd">
                <strong class="weui-dialog__title"></strong>
            </div>
            <div class="weui-dialog__bd">
                <div></div>
            </div>
            <div class="weui-dialog__ft">
                <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script src="./static/js/jquery.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/layui/layui.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<!--
<script src="/static/pay/qrcode.min.js?v=20220914193400001"></script>
-->
<script>

    $('#btn').click(function(){
        $('#layer-tips').hide();
    });

    <?php if ($is_pay_name == 2){ ?>
    $('#layer-tips').show();
    <?php }?>

    var url = '<?php echo $gourl; ?>';

  var urls = 'http%3A%2F%2Fds.alipay.com%2F%3Fscheme%3Dalipays%253a%252f%252fplatformapi%252fstartapp%253fappId%253d77700259%2526page%253dpages%25252Ftransfer%25252Ftransfer%25253Famount%25253D1.00%252526chInfo%25253DmoneyBox%252526remark%25253D2023010614080159402%252526uid%25253D2088732560979864';

    console.log(decodeURIComponent(urls));

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


    function pay() {
        //
        // window.location.href = url;
        // return false;
        // $('#btn_send_jump').on('click',function(){
        //     window.location.href = 'https://qr.alipay.com/fkx11251wwgnw30wvx8w71d?t=1672900550568'.replace(/&amp;/g,'&');
        //     return false;
        // });
        var _alipayh5url = url.replace(/&amp;/g,'&');
        location.href=_alipayh5url;
    }
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer;
        var form = layui.form;
        //监听提交
        $('#btns').click(function() {
            // layer.msg(JSON.stringify(data.field));
            // return false;
            var pay_username = $('input[name="pay_name"]').val();
            $.ajax({
                url: '<?php echo $UPDATE_PAY_USER_NAME ?>',
                method: 'POST',
                dataType: 'json',
                data: {trade_no: '<?php echo $_GET['trade_no'];?>', pay_username: pay_username,},
                success: function (data) {
                    if (data.code != 1) {
                        layer.msg(data.msg, {icon: 2, time: 1500})
                        return false;
                    }
                    layer.msg('提交成功', {icon: 1, time: 1500}, function () {
                        $('#layer-tipss').hide();
                        $('#layer-tips').show();
                    })
                }
            })

        })
    })


</script>

