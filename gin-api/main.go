package main

import (
	"gin-api/router"
	"gin-api/rpc"

	"gin-api/config"

	"github.com/gin-gonic/gin"
)

func main() {
	gin.SetMode(gin.ReleaseMode)

	go rpc.RegisterApiService()
	go rpc.ConsulRegister()

	env, _ := config.Get()
	//引入路由
	r := router.Router()
	//run
	r.Run(":" + env.APP_PORT)

}
