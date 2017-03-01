<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付</title>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>

    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    {!! $jsApiParameters !!},
                    function(res){
                        //WeixinJSBridge.log(res.err_msg);
                        //alert(res.err_code+'---'+res.err_desc+'----'+res.err_msg);
                        if (res.err_msg=='get_brand_wcpay_request:ok') {
                            var tag="{{$tag}}";
                            alert('支付成功!');
                            window.location.href="{{$callback_success_url}}"+tag;
                        }else{
                            //统一做为失败处理
                            alert('支付失败!');
                            window.location.href="{{$callback_error_url}}";
                        }
                    }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
            $('#btn').prop('disabled', true);
        }
    </script>
    <script type="text/javascript">
        //获取共享地址
        function editAddress()
        {
            WeixinJSBridge.invoke(
                    'editAddress',
                    {!! $editAddress !!},
                    function(res){
                        var value1 = res.proviceFirstStageName;
                        var value2 = res.addressCitySecondStageName;
                        var value3 = res.addressCountiesThirdStageName;
                        var value4 = res.addressDetailInfo;
                        var tel = res.telNumber;

                        //alert(value1 + value2 + value3 + value4 + ":" + tel);
                    }
            );
        }
        //TODO:不使用共享地址
        // window.onload = function(){
        // 	if (typeof WeixinJSBridge == "undefined"){
        // 	    if( document.addEventListener ){
        // 	        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
        // 	    }else if (document.attachEvent){
        // 	        document.attachEvent('WeixinJSBridgeReady', editAddress);
        // 	        document.attachEvent('onWeixinJSBridgeReady', editAddress);
        // 	    }
        // 	}else{
        // 		editAddress();
        // 	}
        // };

    </script>
</head>
<body>
<br/>
<div style='text-align:center'>
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">{!! $fee / 100 !!}</span>元</b></font><br/><br/>  </div>
<div align="center">
    <button id='btn' style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" id='callpay' onclick="callpay()" >立即支付</button>
</div>
</body>
</html>