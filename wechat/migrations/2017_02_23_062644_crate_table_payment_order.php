<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTablePaymentOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * user_id 用户ID
         * order_no 需支付商品的订单号
         * trade_no 支付平台支付订单号
         * order_name 支付名称
         * price 订单金额
         * payment_money 订单支付金额
         * payment_platform 支付类型
         * product_type 订单业务类型
         * status 支付状态
         * buyer_id 卖家用户号
         * product_id 订单ID
         * paid_at 支付时间
         * refunded_at 退款时间
         * created_at 创建支付订单时间
         * updated_at 修改支付订单时间
         * deleted_at 删除支付订单时间
         */
        Schema::create('payment_order', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('order_no',20);
            $table->string('trade_no',40);
            $table->string('order_name',45);
            $table->decimal('price',15,2);
            $table->decimal('payment_money',14,2)->nullable();
            $table->tinyInteger('payment_platform')->nullble();
            $table->char('product_type',50)->nullable();
            $table->char('status',50)->nullable();
            $table->string('buyer_id',50)->nullable();
            $table->integer('product_id');
            $table->timestamp('paid_at');
            $table->timestamp('refunded_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_order');
    }
}
