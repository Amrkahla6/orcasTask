<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ApiTrait{

    /**
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getListOfUser1()
    {
        $client = new Client();
        $res = $client->get('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1');
        $result= $res->getBody();
        $data = json_decode($result, true);
        return $data;
    }//End Of Function List Of Users 1

    /**
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getListOfUser2()
    {
        $client = new Client();
        $res = $client->get('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/user_2');
        $result= $res->getBody();
        $data = json_decode($result, true);
        return $data;
    }//End Of Function List Of Users 2


    /**
     * @param $errNum
     * @param $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg'    => $msg
        ]);
    }//End Of Function Return Error


    /**
     * @param $key
     * @param $value
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status'  => true,
            'errNum'  => "S000",
            'msg'     => $msg,
            $key     => $value
        ]);
    }//End Of Function Return Data


}//End Traits
