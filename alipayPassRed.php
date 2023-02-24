<?php
include_once './tools.php';
//$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$user = $_GET['user'];
$orderId = 20000000 + $_GET['remark'];
//$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
//$UPDATE_PAY_CardPwd= 'http://'.decrypt($_GET['user']).'/api/pay/saveCardPwd';
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
$data['key'] = $user;
$ret = json_decode(httpRequest(RECORD_USER_VISITE_INFO, 'post', $data), true);
if ($ret['code'] != 1) {
}
?>
<!DOCTYPE html>
<!-- saved from url=(0118)http://pay5.wuhengshop.com/c/secrect/pay?osn=2023010222254554584506425&t=1672669545&k=db14e32da184104b32ced589935106b4 -->
<html class="fixed js flexbox flexboxlegacy csstransforms csstransforms3d no-overflowscrolling" style=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./static/css/bootstrap.css">
    <link rel="stylesheet" href="./static/css/font-awesome.css">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="./static/css/theme.css">
    <!-- Skin CSS -->
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
<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <form id="post_from" class="layui-form">
        <div class="center-sign" style="padding-top:0">
            <div class="panel panel-sign">
                <div class="panel-body" style="min-height: 100vh">
                    <div style="text-align: center;">
                        <img src="./static/img/alipay.png" style="width: 40%;" alt="">
                    </div>

                    <div class="text-center" style="font-size:16px;line-height:10px;height:20px;color:#000;margin:5px 0;">
                        <span style="font-weight: bold">订单号：</span><span id="ht_order_no"><?php echo $trade_no; ?></span>

                        <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
                    </div>
                    <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:20px 0 20px 0">
                        ￥<span id="balance"><?php echo $order_pay_price; ?></span>
                        <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none;font-size:14px;" data-clipboard-action="copy" data-clipboard-target="#balance">复制金额</button>
                        <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
                    </div>
<!--                    <div class="text-center">-->
<!--                        <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">-->
<!--                            支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">04分36秒</span>-->
<!--                        </p>-->
<!--                    </div>-->
                    <div class="text-center">

                        <input type="text" name="nums" lay-verify="required|number" placeholder="请输入8位红包口令" style="height: 4rem;width:24rem; text-align:center; border: 1px solid #bcb4b4;border-radius: 2px"> <br>
                        <br>
                        <div id="btn_send" lay-submit="" lay-filter="postBtn" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 4rem;line-height: 4rem;width: 80%;margin: 0 auto;border-radius: 8px">
                            已发出红包,确认充值
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <span style="color:#00ff00b5;font-size:1.6rem;font-weight: bold;">支付宝搜索 "口令红包"，点击"发口令红包"</span>
                    </div>
                    <br>
                    <div class="text-center">
                        <span style="color:#ff0000;font-size:1.6rem;font-weight: bold;">红包个数必须填【1】，否则无法上分 <br></span>
                        <span style="color:#ff0000;font-size:1.6rem;font-weight: bold;">红包个数必须填【1】，否则无法上分 <br></span>
                        <span style="color:#ff0000;font-size:1.6rem;font-weight: bold;">红包个数必须填【1】，否则无法上分 <br></span>
                    </div>
                    <br>
                    <div class="text-center">
                        <span style="color:#00ff00b5;font-size:1.6rem;font-weight: bold;">点击"塞钱进红包"，将红包口令输入上方框内</span>
                    </div>
                    <!-- <div style="text-align: center;"">
                          <img src="/static/pay/alipay/alipay_secrect.png?v=2022101118070001" style="width: 90%;" alt="">
                      </div>-->
                </div>
            </div>
        </div>

    </form>
</section>
<div id="dialog_tips" style="display:none;">
    <span style="font-size: 2rem;font-weight: bold;">
   <p style="text-align: center;font-size: 1.8rem;color:#000000;line-height: 3rem;"> 请输入发包人姓名 </p>
   <p style="text-align: center;color:red;font-size: 1.8rem;">请正确填写发红包的支付宝真实姓名</p>
   <p style="text-align: center;color:#000000;font-size: 1.6rem;line-height: 3rem;">
       <input type="text" placeholder="请输入姓名" id="txt_pay_name" value="" style="text-align:center; border: 1px solid #bcb4b4;">
   </p>
    </span>

</div>
<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>

<script>
    var  order_s = "0";
    var pad = function(num){
        if (num < 10){
            num =  '0' + num;
        }
        return num;
    }
    layui.use(['util', 'laydate', 'layer','form'], function () {

        var layer = layui.layer;
        var form = layui.form;
        form.on('submit(postBtn)', function(data){
            var nums = $.trim($('input[name="nums"]').val());
            $.post("<?php echo UPDATE_PAY_CardPwd; ?>", {"sn": "<?php echo $_GET['trade_no']; ?>","cardKey": nums,key : '<?php echo $user;?>'}, function (e) {
                if (e.code == 0) {
                    layer.msg(e.msg,{time:1500},function (){
                        $('input[name="nums"]').val('');
                    })

                } else {
                    layer.msg(e.msg,{time:1500},function (){
                        window.location.href = e.data;
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
</script>


<script>
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
                        alert( "请正确输入付款人姓名");
                        return false;
                    } else {
                        save_payname(txt_pay_name,index);
                    }
                }
            },
        })


    }
    <?php if ($is_pay_name == 1){ ?>
    jump()
    <?php } ?>
    function save_payname (pay_name,index){
        $.post("<?php echo UPDATE_PAY_USER_NAME ?>", {trade_no: '<?php echo $_GET['trade_no'];?>', pay_username: pay_name,key : '<?php echo $user;?>'}, function (e) {
            if (e.code != 1) {
                alert(e.msg)
                return false;
            }else{
                layer.close(index)
                return true;
            }

        }, "json");
        return false;
    }

</script>

</body></html>
