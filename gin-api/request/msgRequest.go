package request

type GetMsgRequest struct {
	Msg string `form:"msg" json:"msg" binding:"required"`
}
