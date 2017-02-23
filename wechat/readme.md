## 使用说明

---
use Eyuan\Cart\CartService;
### CartService使用方法1

CartService可用的方法同laravel facade

---

### CartService 使用方法2：
--
$cart=CartService::getInstance();
--

#### 添加购物车
```
    * @param  integer $goods_id 商品ID
    * @param integer $num 商品数量
    * @param bool $falg 是否依赖商品接口

    $cart->addCart($goods_id,$uid,$num,$falg)
```

#### 更新购物车
```
    * @param integer $id 购物商品数据ID
    * @param array $data ['goods_id'=1,'uid'=>2,'num'=1]

    $cart-> updateCart($data);
```

#### 获取用户购物车
```
    * @param integer $uid 用户ID
    * @param integer $num 每页的数量

    $cart->getCart($uid,$num);
```

#### 清楚购车指定商品
```
    * @param $ids this id or ids

    $cart->delCart($ids);
```
#### 删除购物所有商品
```
    $cart->delAllCart();
```

#### 清空购物车，并释放存储
```
    $cart->clearCart();
```
