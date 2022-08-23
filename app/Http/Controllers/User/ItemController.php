<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendThanksMail;

class ItemController extends Controller
{
    public function __construct()
    {
        //アクセス制限：ログインユーザーのみ
        $this->middleware('auth:users');


        //アクセス制限：販売中のItem
        $this->middleware(function ($request, $next) {

            //Route::get('show/{item}',[ItemController::class, 'show'])->name('items.show');
            //URL('show/{item}')から{item}取得
            $id = $request->route()->parameter('item');

            if(!is_null($id)){
                //以下の条件を満たすProduct::availableItems()に該当itemが存在しているのか
                //Shop ・・ is_selling = true
                //Product ・・ is_selling = true
                //Stockの合計 ・・ 1以上

                $itemId = Product::availableItems()->where('products.id', $id)->exists();
                    if(!$itemId){
                        abort(404);
                    }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {

        $categories = PrimaryCategory::with('secondary')->get();

        //販売可能商品取得
        $products = Product::availableItems()
        ->SelectCategory($request->category ?? '0')
        ->SearchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? '20');

        // dd($stocks,$products);

        //同期的に送信
        // Mail::to('yosiyong@gmail.com')
        // ->send(new TestMail());

        //非同期に送信
        SendThanksMail::dispatch();

        // $categories = PrimaryCategory::with('secondary')
        // ->get();

        // $products = Product::availableItems()
        // ->selectCategory($request->category ?? '0')
        // ->searchKeyword($request->keyword)
        // ->sortOrder($request->sort)
        // ->paginate($request->pagination ?? '20');

        // $products = Product::all();
        // dd($products);

        return view('user.index',
        compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        //指定商品の数量取得
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        //表示の最大値
        if($quantity > 9){
            $quantity = 9;
        }

        return view('user.show',
        compact('product', 'quantity'));
    }
}
