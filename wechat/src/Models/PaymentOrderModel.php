<?php

namespace Eyuan\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentOrderModel extends Model
{

    //表名
    protected $table = 'payment_order';


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];


}
