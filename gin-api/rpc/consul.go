package rpc

import (
	"fmt"
	"gin-api/config"
	"log"
	"net"
	"net/http"
	_ "net/http/pprof"
	"strings"

	"github.com/google/uuid"
	consulapi "github.com/hashicorp/consul/api"
)

// var count int64

// consul 服务端会自己发送请求，来进行健康检查
func consulCheck(w http.ResponseWriter, r *http.Request) {

	// s := "consulCheck" + fmt.Sprint(count) + "remote:" + r.RemoteAddr + " " + r.URL.String()
	// fmt.Println(s)
	// fmt.Fprintln(w, s)
	// count++
}

func ConsulRegister() {
	env, err := config.Get()
	consuConfig := consulapi.DefaultConfig()
	// fmt.Println(env.CONSUL_URL)
	// config.Address = "172.18.167.66:8500"
	consuConfig.Address = env.CONSUL_URL

	client, err := consulapi.NewClient(consuConfig)
	if err != nil {
		log.Fatal("consul client error : ", err)
	}
	serviceName := "GolangApiService"
	instanceId := serviceName + "-" + strings.Replace(uuid.New().String(), "-", "", -1)

	registration := new(consulapi.AgentServiceRegistration)
	registration.ID = instanceId    // 服务节点的名称
	registration.Name = serviceName // 服务名称
	registration.Port = 8006        // 服务端口
	// registration.Tags = []string{"v1000"} // tag，可以为空
	registration.Address = localIP() // 服务 IP

	checkPort := 8080
	registration.Check = &consulapi.AgentServiceCheck{ // 健康检查
		HTTP:                           fmt.Sprintf("http://%s:%d%s", registration.Address, checkPort, "/check"),
		Timeout:                        "3s",
		Interval:                       "5s",  // 健康检查间隔
		DeregisterCriticalServiceAfter: "30s", //check失败后30秒删除本服务，注销时间，相当于过期时间
		// GRPC:     fmt.Sprintf("%v:%v/%v", IP, r.Port, r.Service),// grpc 支持，执行健康检查的地址，service 会传到 Health.Check 函数中
	}

	err = client.Agent().ServiceRegister(registration)
	if err != nil {
		log.Fatal("register server error : ", err)
	}

	http.HandleFunc("/check", consulCheck)
	http.ListenAndServe(fmt.Sprintf(":%d", checkPort), nil)

}

func localIP() string {
	addrs, err := net.InterfaceAddrs()
	if err != nil {
		return ""
	}
	for _, address := range addrs {
		if ipnet, ok := address.(*net.IPNet); ok && !ipnet.IP.IsLoopback() {
			if ipnet.IP.To4() != nil {
				fmt.Println(ipnet.IP.String())
				return ipnet.IP.String()
			}
		}
	}
	return ""
}
