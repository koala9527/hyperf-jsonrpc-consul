#coding=utf-8
from consulclient import ConsulClient
from httpapp import AppServer
import socket 
class HttpServer():
    #新建服务时，需要指定consul服务的 主机，端口，所启动的 服务的 主机 端口 以及 restful http 服务 类
    def __init__(self,port,consulhost,consulport,appClass):
        self.port=port
        self.host=self.get_host_ip()
        self.app=appClass(host=self.get_host_ip(),port=port)
        self.appname=self.app.appname
        self.consulhost=consulhost
        self.consulport=consulport
    def startServer(self):
        client=ConsulClient(host=self.consulhost,port=self.consulport)
        service_id=self.appname+self.host+':'+str(self.port)
        httpcheck='http://'+self.host+':'+str(self.port)+'/check'
        client.register(self.appname,service_id=service_id,address=self.host,port=self.port,tags=['master'],
                        interval='30s',httpcheck=httpcheck)#注册服务 
        self.app.run()#启动服务
    def get_host_ip(self):
        try:
            # Create a new socket using the given address family,
            # socket type and protocol number.
            s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)

            # Connect to a remote socket at address.
            # (The format of address depends on the address family.)
            address = ("8.8.8.8", 80)
            s.connect(address)

            # Return the socket’s own address.
            # This is useful to find out the port number of an IPv4/v6 socket, for instance.
            # (The format of the address returned depends on the address family.)
            sockname = s.getsockname()
            # print(sockname)

            ip = sockname[0]
            port = sockname[1]
        finally:
            s.close()

        return ip

if __name__=='__main__':
    server=HttpServer(8000,'127.0.0.1',8500,AppServer)
    server.startServer()