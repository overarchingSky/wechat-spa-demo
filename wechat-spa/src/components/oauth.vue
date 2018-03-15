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
</script>
