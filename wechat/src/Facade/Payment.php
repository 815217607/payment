<?php
/**
 * Created by PhpStorm.
 * User: fuqixue
 * Date: 2017/1/3
 * Time: 上午2:17
 */

namespace Eyuan\AuthExtend;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade{


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'payment'; }
}