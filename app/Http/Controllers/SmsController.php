<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsController extends Controller
{
   
    function getSms(Request $request){
        $mobile = $request->input('mobile');
        $res = $this->requestSms($mobile);
        return response()->json([
            'msg' => 'success',
        ]);
    }

    function checkSms(Request $request){
        $data= $request->all();
        return response()->json([
            'code' => $data['dxcode'],
            'data' => $data['token']
        ]);
    }

    function requestSms(){
        $url = "http://v.juhe.cn/sms/send";
        $params = array(
    'key'   => '您申请的ApiKey', //您申请的APPKEY
    'mobile'    => '1891351****', //接受短信的用户手机号码
    'tpl_id'    => '111', //您申请的短信模板ID，根据实际情况修改
    'vars' =>'{"code":"3535","name":"聚合数据"}' //模板变量键值对的json类型字符串，根据实际情况修改
        );

$paramstring = http_build_query($params);
$content = $this->juheCurl($url, $paramstring);
    return json_decode($content, true);
}

    function juheCurl($url, $params = false, $ispost = 0)
{
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if ($params) {
            curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    $response = curl_exec($ch);
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
    curl_close($ch);
    return $response;
} 
}