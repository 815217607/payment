<?php

return [
	'wechat' => [
		'app_id'=> env('WEIXIN_PAY_APPID'),
		'payment'=>[
			'merchant_id'        => env('WEIXIN_PAY_MCHID'),
			'key'                => env('WEIXIN_PAY_KEY'),
			'cert_path'          => env('WEIXIN_APY_CERT_PATH'),//'path/to/your/cert.pem',  绝对路径！！！！
			'key_path'           => env('WEIXIN_APY_KEY_PATH'),//'path/to/your/key', 绝对路径！！！！
			'notify_url'         => env('WEIXIN_NOTIFY_PAY_URL'),     // 你也可以在下单时单独设置来想覆盖它
		]
	],

];
