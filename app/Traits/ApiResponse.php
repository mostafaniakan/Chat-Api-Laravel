<?php
namespace App\Traits;




trait ApiResponse{

public function successResponse($message=null,$data,$code,$data2=null){
    return response()->json([
        'status'=>'success',
        'message'=>$message,
        'data'=>$data,
        'data2'=>$data2,
        'code'=>$code,
    ],$code);
}
public function errorResponse($message=null,$code,){
    return response()->json([
        'status'=>'Error',
        'message'=>$message,
        'data'=>[],
        'code'=>$code
    ],$code);
}

}
