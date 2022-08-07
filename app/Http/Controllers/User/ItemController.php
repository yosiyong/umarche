<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
// use App\Mail\TestMail;
// use App\Jobs\SendThanksMail;

class ItemController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:users');

    //     $this->middleware(function ($request, $next) {

    //         $id = $request->route()->parameter('item');
    //         if(!is_null($id)){
    //         $itemId = Product::availableItems()->where('products.id', $id)->exists();
    //             if(!$itemId){
    //                 abort(404);
    //             }
    //         }
    //         return $next($request);
    //     });
    // }

    public function index(Request $request)
    {

        $stocks = DB::table('t_stocks')
        ->select('product_id',
        DB::raw('sum(quantity) as quantity'))
        ->groupBy('product_id')
        ->having('quantity', '>', 1);

        // $stocksをサブクエリとして設定
        // products、shops、stocksをjoin句で紐付けて
        // where句で is_sellingがtrue かの条件指定
        $products = DB::table('products')
        ->joinSub($stocks, 'stock', function($join){
        $join->on('products.id', '=', 'stock.product_id');
        })
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        ->join('secondary_categories', 'products.secondary_category_id', '=','secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        ->join('images as image2', 'products.image2', '=', 'image2.id')
        ->join('images as image3', 'products.image3', '=', 'image3.id')
        ->join('images as image4', 'products.image4', '=', 'image4.id')
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        ->select('products.id as id', 'products.name as name', 'products.price'
        ,'products.sort_order as sort_order'
        ,'products.information', 'secondary_categories.name as category'
        ,'image1.filename as filename')
        ->get();

        // dd($stocks,$products);


        // 同期的に送信
        // Mail::to('test@example.com')
        // ->send(new TestMail());

        // 非同期に送信
        // SendThanksMail::dispatch();

        // $categories = PrimaryCategory::with('secondary')
        // ->get();

        // $products = Product::availableItems()
        // ->selectCategory($request->category ?? '0')
        // ->searchKeyword($request->keyword)
        // ->sortOrder($request->sort)
        // ->paginate($request->pagination ?? '20');

        // $products = Product::all();
        // dd($products);
        return view('user.index',compact('products'));

        // return view('user.index',
        // compact('products', 'categories'));
    }

    // public function show($id)
    // {
    //     $product = Product::findOrFail($id);
    //     $quantity = Stock::where('product_id', $product->id)
    //     ->sum('quantity');

    //     if($quantity > 9){
    //         $quantity = 9;
    //       }

    //     return view('user.show',
    //     compact('product', 'quantity'));
    // }
}
