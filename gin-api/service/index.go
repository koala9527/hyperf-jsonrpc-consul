package service

import "gin-api/request"

func Index(json request.IndexRequest) string {
	return "i am from gin-api@Index:" + json.Msg
}

func GetMsg(Msg string) string {
	return " from gin-api@GetMsg:" + Msg
}
