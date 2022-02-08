<?php

namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success(string $message = null, $data = null, int $code = 200)
	{
        $respon =[
			'status' => 'OK',
			'statusCode' => $code,
            'message' => $message,
		];
        if($data !== null){
            $respon['data'] = $data;
        }
		return response()->json($respon, $code);
	}

	/**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(string $message = null, $data = null, int $code)
	{
        $respon =[
			'status' => 'FAILED',
			'statusCode' => $code,
            'message' => $message,
		];
        if($data !== null && $data !== ''){
            $respon['data'] = $data;
        }
		return response()->json($respon, $code);
	}

}