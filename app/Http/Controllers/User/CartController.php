<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //指定ユーザーのカートを表示
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;

        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        //dd($products, $totalPrice);

        return view('user.cart',
            compact('products', 'totalPrice'));
    }

    //カートに商品追加
    public function add(Request $request)
    {
        $itemInCart = Cart::where('product_id', $request->product_id)
        ->where('user_id', Auth::id())->first();

        if($itemInCart){
            //更新
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();

        } else {
            //新規追加
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        //dd($request,$itemInCart);

        //カート一覧画面表示
        return redirect()->route('user.cart.index');
    }

    //指定ユーザーのカート削除
    public function delete($id)
    {
        Cart::where('product_id', $id)
        ->where('user_id', Auth::id())
        ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        //ユーザーの商品取得してstripに渡す配列を生成
        $lineItems = [];
        foreach($products as $product){
            //在庫情報取得
            $quantity = '';
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');

            //カートの商品数と在庫を比較
            if($product->pivot->quantity > $quantity){
                //在庫が足りない場合
                return redirect()->route('user.cart.index');
            } else {
                //在庫がある場合、strip側に渡す配列に商品情報をセットする。
                $lineItem = [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $product->name,
                            'description' => $product->information,
                            'images' => [asset('storage/products/' . $product->imageFirst->filename )]
                        ],
                        'unit_amount' => $product->price
                    ],
                    'quantity' => $product->pivot->quantity,
                ];
                array_push($lineItems, $lineItem);
            }
        }

        //dd($lineItems);

        //決済する前に在庫を減らす
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('user.cart.success'),
            'cancel_url' => route('user.cart.cancel'),
        ]);

         //return view('user.checkout',compact('session','publicKey'));
        return redirect($session->url, 303);
    }

    public function success()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.items.index');
    }

    public function cancel()
    {
        $user = User::findOrFail(Auth::id());

        foreach($user->products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['add'],
                'quantity' => $product->pivot->quantity
            ]);
        }

        return redirect()->route('user.cart.index');
    }
}
