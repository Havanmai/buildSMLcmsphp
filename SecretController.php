<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SecretController extends Controller
{
    //
	function render(Request $request){
		//get code
		$code = $request->route('id');
		//get detail 
		
		
		$response = Http::withHeaders([
			'Authorization' => 'k62EzE2LTJ5iH8rAcFdA'
		])->get('http://192.168.10.15:8080/services/smartlocker-order/qrcode-server/delivery-codes/code?code='.$code);
		
		//$response = Http::withHeaders([
			//'Authorization' => 'k62EzE2LTJ5iH8rAcFdA'
		//])->get('http://box-api-dev.viettelpost.vn/services/smartlocker-order/qrcode-server/delivery-codes/code?code='.$code);
		
		
		
		$data = $response->object();
		if(is_null($data) || (isset($data->status) && $data->status != 200)){
			echo "Hệ thống đang bận vui lòng thử lại sau";
			return;
		}else if($data->error){
			echo $data->message;
			return;
		}else{
			//check expires 
			$expires = $data->data->expires;
			if($expires){
				$expDate = strtotime($expires);
				$now = time();
				if($now > ($expDate+1000*60*60*24*15)){
					echo "<div style='width: 360px;max-width: 100%;'>Mã bí mật ".$code." chỉ tồn tại 15 ngày, trong thời gian chờ xử lý khiếu nại. Vui lòng liên hệ hỗ trợ khách hàng 19008095 để được hỗ trợ</div>";
					return;
				}else{
					//get video list 
					
					return view('secret',['qrCode' => $request->route('id'),'data'=>$data->data]);
				}
			}else{
				echo "Không tìm thấy thông tin đơn hàng";
			}
		}
		
	}
}
