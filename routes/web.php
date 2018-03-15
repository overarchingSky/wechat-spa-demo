<?php
/**
 * 第二步。 通过微信授权获取 access_token .
 */
Route::get("/getInfoByCode/{code}", function($code){
        // https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $reqUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxb9d4379eb0ea6141&secret=d4624c36b6795d1d99dcf0547af5443d'
            . "&code={$code}&grant_type=authorization_code";

        $client = new \GuzzleHttp\Client([
            'verify' => false ,
        ]);

        $response = $client->get($reqUrl);
        $res = json_decode($response->getBody()->getContents(),true);

        /*
         * { "access_token":"ACCESS_TOKEN",
            "expires_in":7200,
            "refresh_token":"REFRESH_TOKEN",
            "openid":"OPENID",
            "scope":"SCOPE" }
         */
        // 该 access_token 与基础支持的 access_token 不同，只用于获取用户信息. 如果不是很敏感
        // 可以传递给客户端.

        // 第三步。用ak & openid 获取用户信息： 包含头像等内容.
        $infoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token={$res['access_token']}&openid={$res['openid']}&lang=zh_CN";
        $res2 = $client->get($infoUrl);
        $responseInfo = [];
        $responseInfo ['basic'] = $res ;
        $responseInfo ['info'] = json_decode($res2->getBody()->getContents(),true);
        return response()->json($responseInfo);
});