<?php

namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Model\GoodsModel;
use App\Model\OrderModel;
use App\Model\OrderDetailModel;
class OrderController extends Controller
{
    //生成订单
    public function order(){
      $cartInfo=CartModel::where(['uid'=>Auth::id(),'session_id'=>Session::getId()])->get()->toarray();
      //print_r($cartInfo);exit;
      //计算总金额
        $total=0;
        foreach($cartInfo as $k=>$v){
            $total+=$v['goods_price'];
        }
      //入订单表
        $orderInfo=[
            'order_sn'=>OrderModel::orderSn(),
            'total_price'=>$total,
            'uid'=>Auth::id(),
            'ctime'=>time()
        ];
        $order_id=OrderModel::insertGetId($orderInfo);

        //入库详情表
        foreach ($cartInfo as $k =>$v){
            $detail=[
                'order_id'=>$order_id,
                'goods_id'=>$v['goods_id'],
                'goods_name'=>$v['goods_name'],
                'goods_price'=>$v['goods_price'],
                'ctime'=>time()
            ];
            OrderDetailModel::insertGetId($detail);

        }

        header('Refresh:3;url=orderList');
        die('生成订单成功,自动跳转到订单列表页');
    }

    //列表展示
    public function orderList(){
       $orderList=OrderModel::where(['uid'=>Auth::id()])->get()->toArray();
        return view('order.list',['orderList'=>$orderList]);
    }
}
