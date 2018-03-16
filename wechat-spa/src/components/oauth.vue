<template>
  <div></div>
</template>

<script>
  export default{
    data(){
      return {
        url: `https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect`,
        appid: 'wxb9d4379eb0ea6141',
        redirect_uri : null,
        scope: null,
        state: 0,
      }
    },
    methods: {
      replaceUrl(){
         if(this.$route.query.redirect != null) {
           this.redirect_uri = window.location.origin + this.$route.query.redirect;
           this.scope = `snsapi_userinfo`
         }
         return this.url.replace(`APPID`,this.appid)
          .replace(`REDIRECT_URI`,this.redirect_uri)
          .replace(`SCOPE`,this.scope)
          .replace(`STATE`,this.state)
      }
    },
    mounted(){
      if(!localStorage.getItem("openid")){
        window.location.href = this.replaceUrl()
      }
    }
  }





  // app.js
  App({
    onLaunch: function () {
      wx.request({
        url: 'test.php', //仅为示例，并非真实的接口地址
        data: {
        },
        success: function(res) {
          this.globalData.employId = res.employId;
          //由于这里是网络请求，可能会在 Page.onLoad 之后才返回
          // 所以此处加入 callback 以防止这种情况
          if (this.employIdCallback){
            this.employIdCallback(employId);
          }
        }
      })
    },
    globalData: {
      employId: ''
    }
  })
  //index.js
  //获取应用实例
  const app = getApp()
  Page({
    data: {
      albumDisabled: true,
      bindDisabled: false
    },
    onLoad: function () {
    }
  })
  app.employIdCallback = employId => {
    if (employId != '') {
      this.setData({
        albumDisabled: false,
        bindDisabled: true
      });
    }
  }




































</script>
