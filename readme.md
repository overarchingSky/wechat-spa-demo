## 单页服务端获取 openid 示例

### [wechat-spa](https://github.com/clearcodecn/wechat-spa-demo/wechat-spa) 为vue单页应用 
### 外部项目是Laravel项目，可以抛开一切看 routes/web.php 的代码，
### 只是调用了微信端获取用户信息接口. 任何服务端语言都能实现


### 使用Laravel

#### 注意事项
``` 
1. 跨域
2. 错误处理. 该项目里都在中间件处理好了。
```

### 代码在 route.web 这里贴出来精简部分 
``` 

// 第一步单页在业内构造url 重定向到 微信授权. 获取到code然后 提交请求 
//第二步。 通过微信授权获取 access_token .
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


```

### 如果需要接消息接口需要配合nginx. 

- nginx : 
``` 
location /wechat/valid {
    proxy_pass localhost:8000/; 
}
Route::any("/wechat/valid") // 微信消息接口处理

```


### 示例运行： 
``` 
如果之前配置了微信授权域名的不需要重新配置. 
在host里面加入： 127.0.0.1  www.yourdomain 
然后在 wechat-spa 项目运行 npm run dev 监听80端口。

cd wechat-spa && npm run dev 
更改 wechat-spa 项目src/components/oauth.vue#L10 为你自己的appid . 

# 运行Laravel 
composer install && cp .env.example .env && php artisan serve
更改 routes/web.php 里面的 appid 和appsecret 为你自己的. 
![image](https://github.com/clearcodecn/wechat-spa-demo/example.png)
```


### 来源
``` 
1、 前端页面发起授权跳转（跳转到微信官方的那个授权接口）。
2、 用户点击同意后，跳转回刚才的前端页面。
3、 前端页面把获取的code传递给后端接口。
4、 后端通过code获取用户的access_token和openid，然后获取用户信息，
    进行登录/注册，回传JWT的token给前端。5、 前端收到token，即登录成功。

作者：桑弘毅
链接：https://www.zhihu.com/question/39640563/answer/240087513
来源：知乎
著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。3
```