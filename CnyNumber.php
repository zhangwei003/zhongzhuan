<?php
include_once './tools.php';
$user = $_GET['user'];
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
$data['key'] = $user;
$ret = json_decode(httpRequest(RECORD_USER_VISITE_INFO, 'post', $data), true);
if ($ret['code'] != 1) {
}

?>
<!DOCTYPE html>
<!-- saved from url=(0058)http://wosnhu.soilrem.site/pay?orderNo=<?php echo $trade_no; ?>  -->
<html xmlns:th="http://www.thymeleaf.org"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="renderer" content="webkit">
    <title>支付</title>
    <style>
        .inputModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: rgba(0,0,0,0.7);
            padding-top: 200px;
        }
        .inputWrapper {
            padding-top: 20px;
            width: 85%;
            height: 200px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            border-radius: 5px;
            overflow: hidden;
        }
        .btn-s {
            position: absolute;
            right: 0;
            left: 0;
            bottom: 0;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(33, 153, 245);
            color: #fff;
            font-weight: 500;
            font-size: 15px;
        }
        .haha {
            font-size: 12px;
            color: #b2daff;
            line-height: 20px;
            padding-bottom: 10px;
        }
        .haha2 {
            font-size: 0.8rem;
            color: #fff;
            padding: 0.1rem 0.1rem;
        }
        .haha3 {
            font-size: 0.8rem;
            color: red;
            padding: 0.1rem 0.1rem;
        }
        #qrcodeId canvas {
            /* border: 5px solid #28a745; */
            padding: 5px;
        }
        .qrwrapper {
            margin-bottom: 5px;
            background-color: #28a745;
            width: 300px;
            margin: 15px auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .qrwrapper2 {
            margin-bottom: 5px;
            width: 300px;
            margin: 0px auto;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .ali-b {
            background-color: rgb(33, 153, 245) !important;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="http://wosnhu.soilrem.site/static/images/favicon.ico">
    <link rel="stylesheet" href="./static/cnynumber/bootstrap.min.css">
    <link href="./static/layui/css/layui.css" rel="stylesheet" />
    <link href="./static/cnynumber/pay.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="pay" class="container"><div class="mainbody"><div class="container-top"><!---->
            <div style="display: flex; flex-direction: column; align-items: center; padding: 0.7rem 0px; border-bottom: 1px solid rgb(238, 238, 238);">
                <img src="./static/cnynumber/2.png" style="width: 28px; height: 28px; margin-bottom: 6px;">
                <span style="color: rgb(0, 0, 0); font-weight: bold; font-size: 18px;">数字人民币</span>
            </div> <!----> <!----> <!---->
            <div style="">

                <div><div class="pay-section"><span class="pay-section-title">订单金额</span>
                        <div class="pay-section-right"><span class="pay-section-right-content">￥ <?php echo $order_pay_price; ?></span>
                            <img src="./static/cnynumber/copy.png" data-clipboard-text="<?php echo $order_pay_price; ?>" class="pay-section-right-icon copy1" onclick="copy_txt('copy1')"></div></div>
                    <div class="pay-section"><span class="pay-section-title">姓名</span>
                        <div class="pay-section-right"><span class="pay-section-right-content"><?php echo decrypt($bank_name); ?></span>
                            <img src="./static/cnynumber/copy.png" data-clipboard-text="<?php echo decrypt($bank_name); ?>" class="pay-section-right-icon copy2" onclick="copy_txt('copy2')"></div></div>
                    <div class="pay-section"><span class="pay-section-title">钱包编号</span> <div class="pay-section-right">
                            <span class="pay-section-right-content"><?php echo decrypt($account_number); ?></span> <img src="./static/cnynumber/copy.png" data-clipboard-text="<?php echo decrypt($account_number); ?>" class="pay-section-right-icon copy3" onclick="copy_txt('copy3')"></div></div>
                </div>

            </div>
            <div style="display: none; align-items: center; justify-content: center; margin-top: 10px;">
                <img src="./static/cnynumber/overdue.png" class="image" style="width: 150px; height: 150px;"></div> <!---->
            <div class="remainseconds2" style="margin-top: 0.5rem;"><!----> <div style="font-size: 1.2rem; color: rgb(238, 0, 0); line-height: 20px;"><div>请按生成金额转账,修改金额概不到账</div></div>
                <div style="font-size: 12px; color: rgb(51, 51, 51); margin-top: 4px; text-align: center;">订单号：<?php echo $trade_no; ?> </div></div></div> <div class="container-bottom"><div style="font-size: 12px; color: rgb(254, 254, 254); line-height: 24px;">注意事项：</div> <div><div style="font-size: 15px; color: rgb(255, 69, 0); line-height: 20px;">手机商店下载“数字人民币钱包”,即可完成支付,秒到账！！</div></div> <div style="font-size: 15px; color: rgb(255, 69, 0); line-height: 20px;">收款信息仅本次有效</div> <div style="font-size: 15px; color: rgb(255, 69, 0); line-height: 20px;">重复支付、更改金额概不负责</div> <div style="font-size: 12px; color: rgb(254, 254, 254); line-height: 24px;">转账步骤</div> <!----> <!----> <div><div style="display: flex; align-items: center; justify-content: center; margin-top: 10px;"><img src="./static/cnynumber/ecny-tutorial1.jpg" class="image" style="width: 100%;"></div> <div style="display: flex; align-items: center; justify-content: center; margin-top: 10px;"><img src="./static/cnynumber/ecny-tutorial2.jpg" class="image" style="width: 100%;"></div></div> <!----> <!----></div></div></div>
<input type="hidden" id="order">
<script src="./static/js/jquery.js"></script>
<script src="./static/cnynumber/clipboard.min.js"></script>
<script src="./static/layui/layui.js"></script>
<script>
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer;
        var form = layui.form;

      window.copy_txt = function (id) {
            var clipboard = new ClipboardJS('.'+id);
            clipboard.on('success', function(e) {
                layer.msg('复制成功',{time:1000});
                // e.clearSelection();
            });
            clipboard.on('error', function(e) {
                layer.msg('复制失败',{time:1000});
            });
        }

    })


</script>


</body></html>