<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\NewebPayController;

Route::get('/pay', function (Request $request) {
    $newebpay = new NewebPayController;
    $data = $newebpay->getOrder_I();

    // 取得回傳的金流資料
    $merchantID = $data['merchant_id'];
    $tradeInfo = $data['trade_info'];
    $tradeSha = $data['trade_sha'];
    $version = $data['version'];

    $actionUrl = 'https://ccore.newebpay.com/MPG/mpg_gateway'; // 或測試網址

    return response()->make("
        <html>
            <body onload='document.forms[0].submit()'>
                <form method='post' action='{$actionUrl}'>
                    <input type='hidden' name='MerchantID' value='{$merchantID}' />
                    <input type='hidden' name='TradeInfo' value='{$tradeInfo}' />
                    <input type='hidden' name='TradeSha' value='{$tradeSha}' />
                    <input type='hidden' name='Version' value='{$version}' />
                    <noscript><input type='submit' value='Submit' /></noscript>
                </form>
            </body>
        </html>
    ", 200, ['Content-Type' => 'text/html']);
});
