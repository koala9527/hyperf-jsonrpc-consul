package rpc

import (
	"gin-api/service"

	go_jsonrpc "github.com/sunquakes/go-jsonrpc"
)

type GolangApi struct{}

type MsgParams struct {
	Msg string `json:"msg"`
}

type MsgResult = string

//测试获取消息
func (*GolangApi) GetMsg(params *MsgParams, result *MsgResult) error {
	res := service.GetMsg(params.Msg)
	*result = interface{}(res).(MsgResult)
	return nil
}

func RegisterApiService() {
	s, _ := go_jsonrpc.NewServer("http", "0.0.0.0", "8006")
	s.Register(new(GolangApi))
	s.Start()
}
