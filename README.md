# hyperf-jsonrpc-consul

Win10 docker环境下  php hyperf 容器间使用consul注册中心进行 JSONRPC调用例子

新增了Hyperf 通过RPC 调用golang Gin框架的例子
新增了Hyperf 通过RPC 调用Python Flask框架的例子

# 先创建一个consul容器
```
docker run -d --name=consul -p 8500:8500 -e CONSUL_BIND_INTERFACE=eth0 consul
```

# 创建项目

## 安装JSON-RPC
![image.png](https://p3-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/f248f2b4cf90499f9528dba04754d1b6~tplv-k3u1fbpfcp-watermark.image?)

```
docker run -d  -it --name rpc-app -v /D/CodeProject/PhpProject/RPCProject/app:/opt/www -p 8591:9501  -w /opt/www  hyperf/hyperf:7.4-alpine-v3.12-swoole
```
```
docker run -d  -it --name rpc-admin -v /D/CodeProject/PhpProject/RPCProject/admin:/opt/www -p 8592:9501  -w /opt/www  hyperf/hyperf:7.4-alpine-v3.12-swoole
```

```
composer config -g repo.packagist composer <https://mirrors.aliyun.com/composer>
```
## 安装其他依赖

```
composer require hyperf/consul
composer require hyperf/service-governance
composer require hyperf/service-governance-consul
```

## golang gin 框架启动
```
go mod tidy
go run main.go
```

### app->admin
![image.png](https://p6-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/8496666e92ec4811897af824de2bceaa~tplv-k3u1fbpfcp-watermark.image?)
### admin->app
![image.png](https://p1-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/4eff50cfd95e4d8ca03ab3b0b233af9f~tplv-k3u1fbpfcp-watermark.image?)
### admin->gin-api
![image.png](https://p6-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/46203a7a2a2d4e28bbd6a578a40681c9~tplv-k3u1fbpfcp-watermark.image?)
