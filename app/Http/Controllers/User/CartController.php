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

        //ユーザーの商品取得してstripに渡す配列を生成
        $line_items = [];
        foreach($user->products as $product){
            $line_item = [
                'name' => $product->name,
                'description' => $product->description,
                'amount' => $product->price,
                'currency' => 'jpy',
                'quantity' => $product->pivot->quantity,
            ];
            array_push($line_items, $line_item);
        }

        //dd($line_items);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [$line_items],
            'mode' => 'payment',
            'success_url' => route('user.items.index'),
            'cancel_url' => route('user.cart.index'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout',compact('session','publicKey'));
    }
}
