<?php
namespace App\Traits;




trait ApiResponse{

public function successResponse($message=null,$data,$code){
    return response()->json([
        'status'=>'success',
        'message'=>$message,
        'data'=>$data,
    ],$code);
}
public function errorResponse($message=null,$code){
    return response()->json([
        'status'=>'Error',
        'message'=>$message,
        'data'=>[],
    ],$code);
}

}
