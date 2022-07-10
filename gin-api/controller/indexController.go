package controller

import (
	"gin-api/global"
	"gin-api/request"
	"gin-api/service"

	"net/http"

	"github.com/gin-gonic/gin"
)

//
func Index(c *gin.Context) {
	var json request.IndexRequest
	// 将request的body中的数据，自动按照json格式解析到结构体
	if err := c.ShouldBindJSON(&json); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}
	result := global.NewResult(c)
	data := service.Index(json)
	result.Success(data)
}
