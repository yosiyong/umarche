<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {

            //アクセスURLからimageのid取得
            $id = $request->route()->parameter('image');
            if(!is_null($id)){ // null判定

                //shopのIdに紐づくownerのIDを取得
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $imageId = (int)$imagesOwnerId; // キャスト 文字列→数値に型変換

                //ログイン中のImageのIdとアクセスOwnerIdと同じかチェック
                if($imageId !== Auth::id()){ // 同じでなかったら
                    abort(404); // 404画面表示
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        //ログイン中のImageのidに紐づくShopデータ取得
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        return view('owner.images.index', compact('images'));
    }

    public function create()
    {
        return view('owner.images.create');
    }

    public function store(UploadImageRequest $request)
    {
        $imageFiles = $request->file('files');

        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                //--//リサイズして保存:ファイル名,保存フォルダ名
                $fileNameToStore = ImageService::upload($imageFile,'products');

                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }

            return redirect()
            ->route('owner.images.index')
            ->with(['message'=>'画像登録を実施しました。','status'=>'info']);
        }
    }

    public function edit($id)
    {
        //指定idの変更
        $image = Image::findOrFail($id);

        return view('owner.images.edit',compact('image'));
    }

    public function update(UploadImageRequest $request, $id)
    {
        //データ検証
        $request->validate([
            'title' => 'string|max:50'
        ]);

        //指定idのデータをDB更新
        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message'=>'画像情報を更新しました。','status'=>'info']);
    }

    public function destroy($id)
    {


        //image取得
        $image = Image::findOrFail($id);

        //productにimageデータを持っているのか
        $imageInProducts = Product::where('image1', $image->id)
        ->orWhere('image2', $image->id)
        ->orWhere('image3', $image->id)
        ->orWhere('image4', $image->id)
        ->get();

        //productにimageデータが存在する場合
        if($imageInProducts){
            //Prodductのimageデータをnullにする
            $imageInProducts->each(function($product) use($image){
                if($product->image1 === $image->id){
                    $product->image1 = null;
                    $product->save();
                }
                if($product->image2 === $image->id){
                    $product->image2 = null;
                    $product->save();
                }
                if($product->image3 === $image->id){
                    $product->image3 = null;
                    $product->save();
                }
                if($product->image4 === $image->id){
                    $product->image4 = null;
                    $product->save();
                }
            });
        }


        $filePath = 'public/products/' . $image->filename;

        if(Storage::exists($filePath)){
            //ストレージのファイル削除
            Storage::delete($filePath);
        }
         //指定IDのデータを削除
         Image::findOrFail($id)->delete();

         return redirect()
         ->route('owner.images.index')
         ->with(['message'=>'画像を削除しました。','status'=>'alert']);
    }
}
