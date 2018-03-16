import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import axios from 'axios'

Vue.use(Router)

var router = new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      meta: {
        requireAuth: true,  // 添加该字段，表示进入这个路由是需要登录的
      },
      component: HelloWorld
    },{
      // oauth 跳转.
      path: '/oauth',
      name: 'oauth',
      component: (resolve => {
        require(['@/components/oauth'], resolve)
      })
    },{
      path: '/getcode',
      name: 'getcode',
      component: (resolve => {
        require(['@/components/getcode'], resolve)
      })
    }
  ]
})

router.beforeEach((to, from,next) => {
  // 有的页面需要openid 但是没有的.
  if (to.meta.requireAuth) {  // 判断该路由是否需要登录权限
    if (localStorage.getItem("openid")) {  // 通过vuex state获取当前的token是否存在
      next();
    }
    else {
      var arr = window.location.search.replace('?','').split('&')
      if(arr.length != 1) {
        var query = {};
        for(var i = 0 ; i < arr.length ; i ++) {
          var tmp = arr[i].split('=')
          query[tmp[0]] = tmp[1];
        }
        if(query.state != null && query.code != null) {
          axios.get("http://127.0.0.1:8000/server.php?code=" + query.code)
            .then((res) => {
              localStorage.setItem("openid",res.data.basic.openid);
              // 需要构造一下.
              next({
                path: '/'
              })
            });
        }
      }
      else{
        //
        next({
          path: '/oauth',
          query: {redirect: to.fullPath}  // 将跳转的路由path作为参数，登录成功后跳转到该路由
        })
      }
    }
  }
  else {
    next();
  }
})






export default router
