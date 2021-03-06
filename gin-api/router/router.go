package router

import (
	"gin-api/controller"
	"gin-api/global"
	"gin-api/middlewares"
	"log"
	"net/http"
	"runtime/debug"

	"github.com/gin-gonic/gin"
)

//Router 路由方法
func Router() *gin.Engine {
	router := gin.Default()
	//处理异常
	router.NoRoute(HandleNotFound)
	router.NoMethod(HandleNotFound)
	router.Use(Recover)
	router.Use(middlewares.Cors())
	router.GET("/", func(c *gin.Context) {
		c.JSON(http.StatusOK, 1)
		return
	})
	my := router.Group("/test")
	{
		// 路径映射
		// api:=controller.NewDyController()
		my.POST("/index", controller.Index)
	}

	return router
}

//HandleNotFound 404
func HandleNotFound(c *gin.Context) {
	global.NewResult(c).Error(404, "资源未找到", nil)
	return
}

//Recover 500
func Recover(c *gin.Context) {
	defer func() {
		if r := recover(); r != nil {
			//打印错误堆栈信息
			log.Printf("panic: %v\n", r)
			debug.PrintStack()
			global.NewResult(c).Error(500, "服务器内部错误", nil)
		}
	}()
	//继续后续接口调用
	c.Next()
}
