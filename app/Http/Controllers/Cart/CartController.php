<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Model\CartModel;
use App\Model\GoodsModel;
class CartController extends Controller
{

    /*
     * 购物车展示
     */
    public function cartIndex()
    {
        $cartInfo=CartModel::where(['uid'=>Auth::id(),'session_id'=>Session::getId()])->get()->ToArray();
        if($cartInfo){
           $total_price=0;
           foreach($cartInfo as $k=>$v){
              $goodsInfo=GoodsModel::where('goods_id',$v['goods_id'])->first()->toarray();
              $total_price+=$goodsInfo['goods_price'];
              $goods_list[]=$goodsInfo;
           }
           $goods=[
               'goods_list'=>$goods_list,
               'total_price'=>$total_price / 100
           ];
           return view('cart.index',$goods);
        }else{
            header('Refresh:5;url=/');
            die("购物车没数据，自动跳转至首页");
        }
    }


    /*
     * 加入购物车
     * $goods_id
     */
    public function cartAdd($goods_id=0)
    {
        //goods_id非空
        if(empty($goods_id)){
            header('Refresh:3;url=/cartIndex');
            die("请选择商品，3秒后自动跳转至购物车");
        }
        $goods=GoodsModel::where('goods_id',$goods_id)->first();
        //添加购物车
        $cartInfo=[
            'goods_id'=>$goods->goods_id,
            'goods_name'=>$goods->goods_name,
            'goods_price'=>$goods->goods_price,
            'uid'=>Auth::id(),
            'session_id'=>Session::getId()
        ];
        $cartRes=CartModel::insert($cartInfo);
        if($cartRes){
            header('Refresh:5;url=/cartIndex');
            die("添加购物车成功，自动跳转至购物车");
        }else{
            header('Refresh:3;url=/');
            die("添加购物车失败");
        }
    }

}
