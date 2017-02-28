<?php
/**
 * Created by PhpStorm.
 * User: fuqixue
 * Date: 2017/1/3
 * Time: 上午2:17
 */

namespace Eyuan\Payment\Wechat;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {
//
//	/**
//	 * Indicates if loading of the provider is deferred.
//	 *
//	 * @var bool
//	 */
//	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configPath = __DIR__ . '/../config/payment.php';
		$this->mergeConfigFrom($configPath, 'payment');

		$this->app->singleton('payment', function () {
			return new PaymentService;
		});
		$this->app->alias('Payment', 'Eyuan\Payment\PaymentService');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Payment'];
	}


	public function boot ()
	{
		$this->publishes([
			__DIR__.'/../config/payment.php' => config_path('payment.php'),
			__DIR__.'/../migrations/' => database_path('migrations'),
			__DIR__.'/../payment/' => resource_path('views/frontend/payment'),
		],'payment');
	}

}
