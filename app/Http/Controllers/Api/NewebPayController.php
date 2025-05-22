<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewebPayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getOrder(Request $request): JsonResponse
    {
        $timeStamp = time();
        $orderNo = 'Vanespl_ec_' . $timeStamp;
        $id = Config::get('newebpay.ID');
        $key = Config::get('newebpay.HashKey');
        $iv = Config::get('newebpay.HashIV');
        $version = '2.0';

        $data1=http_build_query(array(
            'MerchantID'=>$id,
            'RespondType'=>'JSON',
            'TimeStamp'=>$timeStamp,
            'Version'=>$version,
            'MerchantOrderNo'=>$orderNo,
            'Amt'=>'30',
            'ItemDesc'=>'test',
            'Email'=>'a50949359@gmail.com',
            ));

        $edata1=bin2hex(openssl_encrypt($data1, "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv));

        $hashs="HashKey=".$key."&".$edata1."&HashIV=".$iv;
        $hash=strtoupper(hash("sha256",$hashs));

        return response()->json([
            'merchant_id' => $id,
            'trade_info' => $edata1,
            'trade_sha' => $hash,
            'version' => $version,
        ], 200);
    }

    public function callback (Request $request) {
        // 藍新會回傳 form-urlencoded 資料
        $data = $request->all();
        
        // 解密 TradeInfo
        $tradeInfo = json_decode(openssl_decrypt(base64_decode($data['TradeInfo']), 'AES-256-CBC', '你的Key', 0, '你的IV'), true);

        // 這裡可以顯示訂單完成畫面或 redirect
        return view('payment.return', ['info' => $tradeInfo]);
    }

    public function notify (Request $request) {
        $data = $request->all();
        Log::info('NewebPay Notify Data:', $data);

        $key = Config::get('newebpay.HashKey');
        $iv = Config::get('newebpay.HashIV');

        // 解密 TradeInfo
        $tradeInfo = json_decode(openssl_decrypt(base64_decode($data['TradeInfo']), 'AES-256-CBC', $key, 0, $iv), true);

        // 處理付款成功的邏輯（例如更新訂單狀態）
        if ($data['Status'] === 'SUCCESS') {
            $tradeInfo = $data['TradeInfo'];
            if (strtoupper(hash("sha256", $tradeInfo)) !== $data['TradeSha']) {
                $binaryData = hex2bin($tradeInfo);
                $decrypted = openssl_decrypt($binaryData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
                $decoded = urldecode($decrypted); // 將 %XX 編碼轉換成原始中文字
                parse_str($decoded, $result);
                Log::info('NewebPay Notify Data:', $result);
            } else {
                Log::info('資料驗證失敗');
                return;
            }

        } else {
            Log::info('Status:', 'failed');
            return;
        }

        return;
    }

    public function getOrder_I()
    {
        $timeStamp = time();
        $orderNo = 'Vanespl_ec_' . $timeStamp;
        $id = Config::get('newebpay.ID');
        $key = Config::get('newebpay.HashKey');
        $iv = Config::get('newebpay.HashIV');
        $version = '2.0';

        $data1=http_build_query(array(
            'MerchantID'=>$id,
            'RespondType'=>'String',
            'TimeStamp'=>$timeStamp,
            'Version'=>$version,
            'MerchantOrderNo'=>$orderNo,
            'Amt'=>'30',
            'ItemDesc'=>'test',
            'Email'=>'a50949359@gmail.com',
            'EmailModify'=>0,
            // 'CREDIT'=>1,
            'NotifyURL'=>'https://2372-125-227-161-91.ngrok-free.app/api/pay/notify',
            'ClientBackURL'=>'http://google.com',
            'OrderDetail'=>['good1'=>'test1','good2'=>'test2','good3'=>'test3','good4'=>'test4'],
            'TokenTerm'=>'a050949359@gmail.com'
            ));

        $edata1=bin2hex(openssl_encrypt($data1, "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv));

        $hashs="HashKey=".$key."&".$edata1."&HashIV=".$iv;
        $hash=strtoupper(hash("sha256",$hashs));

        return [
            'merchant_id' => $id,
            'trade_info' => $edata1,
            'trade_sha' => $hash,
            'version' => $version,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
