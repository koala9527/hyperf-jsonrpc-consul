from flask import Flask,request,jsonify, Response
import socket
import json
import consul
from flask_jsonrpc import JSONRPC
from jsonrpcserver import method, Result, Success, dispatch

PORT = 8000
CONSUL_PORT = 8500
HOST = '127.0.0.1'

app = Flask("application")

# 注册服务到 Consul
def register_service():
    cursor = consul.Consul(host=HOST, port=CONSUL_PORT, scheme='http')
    service_address = get_host_ip()  # a better way to get ip
    # cursor.apply_remote_config(namespace='mynamespace/')
    cursor.agent.service.register(
        name='PythonApiService', address=service_address, port=PORT,
        check=consul.Check().tcp(host=service_address, port=PORT,
                                 interval='5s',
                                 timeout='30s', deregister='30s'),

    )

def get_host_ip():
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.connect(('8.8.8.8', 80))
        ip = s.getsockname()[0]
    finally:
        s.close()
    return ip

@app.route('/check', methods=['GET'])
def health():
    response = {
        "jsonrpc": "2.0",
    }
    return jsonify(response)

# jsonrpc = JSONRPC(app, '/')
@app.route('/', methods=['POST'])
# @jsonrpc.method(validate=False)
def getMsg():
    print(request.json)
    res = {
    "jsonrpc": "2.0",
    "id": "61025bc35e07d",
    "error": {
        "code": -32000,
        "message": "user not found",
        "data": {
            "class": "RuntimeException",
            "code": 0,
            "message": "user not found"
        }
    },
    "context": []
}
    return Response(
        dispatch(res), content_type="application/json"
    )

# {'jsonrpc': '2.0', 'method': '/python_api/getMsg', 'params': ['Im admin'], 'id': '63e798b5306fb', 'context': []}
if __name__ == '__main__':
    register_service()
    app.run(host='0.0.0.0', port=PORT, debug=True)