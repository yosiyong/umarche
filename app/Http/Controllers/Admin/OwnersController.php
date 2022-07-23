<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;               //Eloquent
use Illuminate\Support\Facades\DB;  //QueryBuilder
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Models\Shop;

class OwnersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        //一覧データ
        // $e_all = Owner::all();
        $owners = OWner::select('id', 'name','email','created_at')->paginate(3);

        return view('admin.owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //新規作成
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //データ検証
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|confirmed|min:8',
        ]);

        try{
            DB::transaction(function() use($request){
                 //オーナー新規作成
                $owner = Owner::create([
                                'name' => $request->name,
                                'email' => $request->email,
                                'password' => Hash::make($request->password),
                                ]);

                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => 'ショップ名',
                    'information' => 'ショップ情報',
                    'filename' => '',
                    'is_selling' => true
                ]);

            }, 2);
        }catch(Throwable $e){
            //storage\logs\laravel.log
            Log::error($e);
            throw $e;
        }

        return redirect()
        ->route('admin.owners.index')
        ->with(['message'=>'オーナー情報を登録しました。','status'=>'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //指定idの変更
        $owner = Owner::findOrFail($id);

        return view('admin.owners.edit',compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //指定idのデータをDB更新
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message'=>'オーナー情報を更新しました。','status'=>'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //指定IDのデータを削除
        Owner::findOrFail($id)->delete();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message'=>'オーナー情報を削除しました。','status'=>'alert']);
    }

    public function expiredOwnerIndex(){
        $expiredOwners = Owner::onlyTrashed()->get();
        return view('admin.expired-owners',
        compact('expiredOwners'));
    }

    public function expiredOwnerDestroy($id){
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()
        ->route('admin.expired-owners.index')
        ->with(['message'=>'オーナー情報を完全に削除しました。','status'=>'alert']);
    }
}
