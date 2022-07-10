package request

// 定义接收数据的结构体
type IndexRequest struct {
	// binding:"required"修饰的字段，若接收为空值，则报错，是必须字段
	Msg string `form:"msg" json:"msg" binding:"required"`
}
