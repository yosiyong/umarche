<?php

namespace App\Services;
use App\Models\Product;
use App\Models\Cart;

class CartService
{
    //カートデータに紐づく複数のDBデータを一つの配列にマージする処理
  public static function getItemsInCart($items)
  {
    $products = [];

    //カート情報分以下処理
    foreach($items as $item)
    {
        //カート紐づく商品情報取得
        $p = Product::findOrFail($item->product_id);

        //カート商品を販売した店舗のオナー情報取得
        $owner = $p->shop->owner->select('name', 'email')->first()->toArray();

        //オーナー名の項目名nameをownernameに変更して配列作成（他の情報にもnameが存在するため）
        $values = array_values($owner);
        $keys = ['ownerName', 'email'];
        $ownerInfo = array_combine($keys, $values);

        //カート内の商品情報取得
        $product = Product::where('id', $item->product_id)
        ->select('id', 'name', 'price')->get()->toArray();

        //商品の在庫情報取得
        $quantity = Cart::where('product_id', $item->product_id)
        ->select('quantity')->get()->toArray();

        //オナー情報,商品情報、在庫情報を一つの配列としてマージ
        $result = array_merge($product[0], $ownerInfo, $quantity[0]);

        array_push($products, $result);
    }

    return $products;

  }
}
