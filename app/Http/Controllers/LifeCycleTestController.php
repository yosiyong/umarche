<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class LifeCycleTestController extends Controller
{

    public function showServiceContainerTest()
    {
        //サービス登録
        app()->bind('lifecycleTest',function(){
            return 'ライフサイクルテスト';
        });

        //サービス参照
        $test = app()->make('lifecycleTest');

        //サービスコンテナを使わないパータン
        $message = new Message();
        $sample = new Sample($message);
        $sample->run();

        //サービスコンテナを使うパータン
        app()->bind('sample', Sample::class);
        $sample2 = app()->make('sample');
        $sample2->run();

        dd($test,app());
    }
}

class Sample {
    public $message;
    public function __construct(Message $message){
        $this->message = $message;
    }

    public function run() {
        $this->message->send();
    }
}

class Message{
    public function send() {
        echo('メッセージ表示');
    }
}
