#coding=utf-8
from flask import Flask,jsonify


class BaseServer():#服务的基类，提供了flask App的基本操作以及一个检测公共的check接口
    app = Flask(__name__)
    @app.route('/check', methods=['GET'])#健康检查url  
    def check():
        return 'success'
    def __init__(self,host,port):
        self.host=host
        self.port=port
        self.app=BaseServer.app
    def run(self):
        self.app.run(host=self.host,port=self.port,threaded=True)
class AppServer(BaseServer):#http 服务应用类，在基类的基础上实现了特定的业务接口
    app=BaseServer.app
    appname='PythonApiService'#应用名称
    @app.route('/getMsg', methods=['GET'])#服务路径 和  appname相同
    def scrapy():
        data = {'res': ' from python-api@GetMsg:','name':'test'}
        return jsonify(data)