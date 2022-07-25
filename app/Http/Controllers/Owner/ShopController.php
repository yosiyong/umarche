<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;

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
        //$ownerId = Auth::id();

        //ログイン中のowneridに紐づくShopデータ取得
        $shops = Shop::where('owner_id', Auth::id())->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id)
    {
        //指定idの変更
        $shop = Shop::findOrFail($id);

        return view('owner.shops.edit',compact('shop'));
    }

    public function update(UploadImageRequest $request, $id)
    {
        $imageFile = $request->image; //一時保存

        //ファイル名あり、かつ、アップロードされているのか
        if(!is_null($imageFile) && $imageFile->isValid() ){

            //リサイズなし保存
            //指定パスに（なければ、フォルダ作成）、指定ファイルを保存（ファイル名自動生成）
            //storage\app\public\shopsに$imageFile名で保存
            //Storage::putFile('public/shops', $imageFile);

            //--//リサイズして保存
            //アップロードしたファイル
            $fileName = uniqid(rand().'_');
            $extension = $imageFile->extension();
            $fileNameToStore = $fileName. '.' . $extension;

            //リサイズ
            $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
            //保存
            Storage::put('public/shops/' . $fileNameToStore,  $resizedImage);
        }

        return redirect()->route('owner.shops.index');
    }
}
