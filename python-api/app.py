from flask import Flask,request,jsonify
import socket
import consul

PORT = 8000
CONSUL_PORT = 8500
HOST = '127.0.0.1'


app = Flask(__name__)

# 注册服务到 Consul
def register_service():
    cursor = consul.Consul(host=HOST, port=CONSUL_PORT, scheme='http')
    service_address = get_host_ip()  # a better way to get ip
    print(service_address)
    cursor.agent.service.register(
        name='PythonApiService', address=service_address, port=PORT,
        check=consul.Check().tcp(host=service_address, port=PORT,
                                 interval='5s',
                                 timeout='30s', deregister='30s')
    )

def get_host_ip():
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.connect(('8.8.8.8', 80))
        ip = s.getsockname()[0]
    finally:
        s.close()
    return ip

# 定义 JSON-RPC 调用的方法
@app.route('/', methods=['POST'])
def json_rpc():
    # 解析 JSON-RPC 请求
    json_request = request.get_json()
    method = json_request.get("method")
    params = json_request.get("params")
    result = ' from python-api@GetMsg:'+params[0]
    # 返回 JSON-RPC 响应
    response = {
        "jsonrpc": "3.0",
        "result": result,
        "id": json_request.get("id")
    }
    return jsonify(response), 200

# 定义 JSON-RPC 调用的方法
@app.route('/check', methods=['GET'])
def health():
    response = {
        "jsonrpc": "2.0",
    }

    return jsonify(response), 200

if __name__ == '__main__':
    register_service()
    app.run(host='0.0.0.0', port=PORT, debug=True)