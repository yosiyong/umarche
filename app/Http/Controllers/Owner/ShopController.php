<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('shop'); //アクセスURLからshopのid取得
            if(!is_null($id)){ // null判定

                //shopのIdに紐づくownerのIDを取得
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換

                //ログイン中のOwnerのID取得
                $ownerId = Auth::id();

                //ログイン中のOwnerIdとアクセスOwnerIdと同じかチェック
                if($shopId !== $ownerId){ // 同じでなかったら
                    abort(404); // 404画面表示
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        //ログイン中のidを取得
        $ownerId = Auth::id();

        //Shopデータ取得
        $shops = Shop::where('owner_id', $ownerId)->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id)
    {
        //指定idの変更
        $shop = Shop::findOrFail($id);

        return view('shops.edit',compact('shop'));
    }

    public function update(Request $request, $id)
    {
        //指定idのデータをDB更新
        $shop = Shop::findOrFail($id);
        $shop->name = $request->name;
        $shop->information = $request->information;
        $shop->filename = $request->filename;
        $shop->is_selling = $request->is_selling;
        $shop->save();

        return redirect()
        ->route('shops.index')
        ->with(['message'=>'店舗情報を更新しました。','status'=>'info']);
    }
}
