<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_CardPwd= 'http://'.decrypt($_GET['user']).'/api/pay/saveCardPwd';
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
    </style>
    <title>支付</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/layui/css/layui.css" rel="stylesheet" />
</head>

<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <form id="post_from" class="layui-form">
        <div class="center-sign" style="padding-top:0">
            <div class="panel panel-sign">
                <div class="panel-body" style="min-height: 100vh">

                    <div class="text-center">
                        <img src="./static/img/jd_logo.jpg" style="width: 20%;" alt="">
                    </div>
                    <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:20px 0 20px 0">
                        ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                    </div>
<!--                    <div class="text-center">-->
<!--                        <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">-->
<!--                            支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">09分37秒</span>-->
<!--                        </p>-->
<!--                    </div>-->

                    <div style="text-align:center ;font-size:16px;color:red;margin-top:15px;font-weight: bold;">
                        <p>下单购买后，查看卡密</p>
                        <p>返回本页面填写卡密，提交充值</p>
                    </div>
                    <div class="text-center">

                        <input type="text" name="nums" lay-verify="required" placeholder="请输入京东E卡的卡密" style="height: 4rem;width:24rem; text-align:center; border: 1px solid #bcb4b4;border-radius: 2px"> <br>
                        <br>
                        <div id="btn_send" lay-submit="" lay-filter="postBtn" style="background: #1b76fc;color: #ffffff;font-size: 1.3rem;height: 4rem;line-height: 4rem;width: 90%;margin: 0 auto;border-radius: 8px">
                            点击提交充值
                        </div>

                        <br>
                        <div class="text-center qrcode_img">

                        </div>
                        <br>
                        <div class="text-center">
                            <div id="btn_jump" style="background: #ff0000;color: #ffffff;font-size: 1.3rem;height: 4rem;line-height: 4rem;width: 90%;margin: 0 auto;border-radius: 8px">
                                点击打开京东APP购买京东E卡
                            </div>
                        </div>
                    </div>
                    <div class="text-center" style="color:#ff0000;font-weight: bold;font-size:16px;line-height:20px;height:30px;margin:25px 0;">
                        <p>如果无法跳转到京东APP，请截屏保存二维码，微信扫一扫购买</p>
                    </div>
                    <br>
                    <div style="font-size:14px;font-weight: bold;color:red;margin-top:5px;">
                        充值教程：
                    </div>

                    <div style="text-align:left ;font-size:14px;color:red;margin-top:5px;font-weight: bold;line-height: 18px;">
                        <p>1.点击跳转到京东APP(手机上需要安装京东APP)</p>
                        <p>2.购买与充值订单等额的电子卡</p>
                        <p>3.进入订单详情，查看卡密</p>
                        <p>4.复制卡密，返回到本页面，将卡密粘贴到上方输入框内，点击提交充值即可</p>
                    </div>
                    <br>
                    <div style="text-align: center;">
                        <img src="./static/img/jd_tips_2.jpg" style="width: 100%;" alt="">
                    </div>
                </div>
            </div>
        </div>

    </form>
</section>
<div id="dialog_tips" style="display:none;">
    <span style="text-align: center;font-size: 1.8rem;font-weight: bold;color: #423f3f;">

   <p style="text-align: center;color:red;"> ⚠️⚠️⚠️ 注意注意注意⚠️⚠️⚠️</p>
    <br>
    <p style="text-align: center;color:red;">在京东收银台卡密获取方式请选择：手机号码！</p>
        <br>
    <p style="text-align: center;color:red;">微信内支付时请使用银行卡付款，尽量不要用微信余额付款！</p>
    </span>
</div>
<script src="./static/js/jquery.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<script src="./static/layui/layui.js"></script>

<script>
    var url = '';
        <?php if($_GET['order_pay_price'] == '100.00'){ ?>
     url = 'https://item.m.jd.com/product/1107845.html?_fd=jdm&price=100.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=4&csid=6771e7560e52522c02cc45361ef8340a_1675926016306_1_1675926016306&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
        <?php  }elseif ($_GET['order_pay_price'] == '200.00'){ ?>
    url = 'https://item.m.jd.com/product/1107847.html?_fd=jdm&price=200.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=3&csid=6771e7560e52522c02cc45361ef8340a_1675946224032_2_1675946232942&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '300.00'){?>
    url = 'https://item.m.jd.com/product/1107846.html?_fd=jdm&price=300.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=29&csid=6771e7560e52522c02cc45361ef8340a_1675946279004_3_1675946281105&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '500.00'){?>
    url = 'https://item.m.jd.com/product/1107843.html?_fd=jdm&price=500.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=7&csid=6771e7560e52522c02cc45361ef8340a_1675946709157_1_1675946709157&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '600.00'){?>
    url = 'https://item.m.jd.com/product/1962859.html?_fd=jdm&price=600.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=27&csid=6771e7560e52522c02cc45361ef8340a_1675946279004_3_1675946281105&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '800.00'){?>
    url = 'https://item.m.jd.com/product/1107833.html?_fd=jdm&price=800.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=7&csid=6771e7560e52522c02cc45361ef8340a_1675946709157_1_1675946709157&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '1000.00'){?>
    url = 'https://item.m.jd.com/product/1107842.html?_fd=jdm&price=1000.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=7&csid=6771e7560e52522c02cc45361ef8340a_1675946709157_1_1675946709157&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1'
    <?php } elseif ($_GET['order_pay_price'] == '2000.00'){?>
    url = 'https://item.m.jd.com/product/3348254.html?_fd=jdm&price=2000.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=25&csid=6771e7560e52522c02cc45361ef8340a_1675946279004_3_1675946281105&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '3000.00'){?>
    url = 'https://item.m.jd.com/product/3522645.html?_fd=jdm&price=3000.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=7&csid=6771e7560e52522c02cc45361ef8340a_1675946224032_2_1675946232942&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '4000.00'){?>
    url = 'https://item.m.jd.com/product/3348256.html?_fd=jdm&price=4000.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=1&csid=6771e7560e52522c02cc45361ef8340a_1675946709157_1_1675946709157&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } elseif ($_GET['order_pay_price'] == '5000.00'){?>
    url = 'https://item.m.jd.com/product/3020581.html?_fd=jdm&price=5000.00&fs=1&sceneval=2&jxsid=16759260094452298063&pos=8&csid=6771e7560e52522c02cc45361ef8340a_1675946224032_1_1675946224032&ss_projid=undefined&ss_expid=undefined&ss_ruleid=undefined&scan_orig=search.1.undefined.undefined.undefinedsearch.2.undefined.undefined&ss_sexpid=undefined&ss_sruleid=undefined&ss_symbol=8&ss_mtest=shop_entrance_t3&key=%E4%BA%AC%E4%B8%9CE%E5%8D%A1';
    <?php } ?>

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



    var  order_s = "<?php echo $_GET['trade_no']; ?>";
    var pad = function(num){
        if (num < 10){
            num =  '0' + num;
        }
        return num;
    }
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
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1107845.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    <?php  }elseif ($_GET['order_pay_price'] == '200.00'){ ?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1107847.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    <?php } elseif ($_GET['order_pay_price'] == '300.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1107846.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1107846.html';
    <?php } elseif ($_GET['order_pay_price'] == '500.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1107843.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1107843.html';
    <?php } elseif ($_GET['order_pay_price'] == '600.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1962859.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1962859.html';
    <?php } elseif ($_GET['order_pay_price'] == '800.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1107833.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1107833.html';
    <?php } elseif ($_GET['order_pay_price'] == '1000.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/1107842.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/1107842.html'
    <?php } elseif ($_GET['order_pay_price'] == '2000.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/3348254.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/3348254.html';
    <?php } elseif ($_GET['order_pay_price'] == '3000.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/3522645.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/3522645.html';
    <?php } elseif ($_GET['order_pay_price'] == '4000.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/3348256.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/3348256.html';
    <?php } elseif ($_GET['order_pay_price'] == '5000.00'){?>
    $('#btn_jump').on('click',function(){
        window.location.href = 'openApp.jdMobile://virtual?params='+ encodeURIComponent('{"category":"jump","des":"m","sourceValue":"babel-act","sourceType":"babel","url":"https://item.m.jd.com/product/3020581.html","M_sourceFrom":"h5auto","msf_type":"auto"}');
        return false;
    });
    // urls = 'https://item.m.jd.com/product/3020581.html';
    <?php } ?>





    var timeLimit = 0;
    function jump() {
        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn:"我已知晓",
            shadeClose:false,
            yes: function(index, layero){
                layer.close(index);
            },
        })
    }

    jump();
</script>

</body></html>
