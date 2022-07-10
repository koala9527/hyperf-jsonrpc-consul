#源镜像
FROM golang:alpine 




ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    GO111MODULE=on \
    GOPROXY=https://goproxy.cn



##
# ---------- building ----------
##
RUN set -ex \
    # change apk source repo
    && sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories \
    && apk update \
    && apk add yasm && apk add ffmpeg && apk add imagemagick

RUN ffmpeg -formats

WORKDIR /build
COPY . .
RUN go mod download
RUN CGO_ENABLED=0 GOARCH=amd64 GOOS=linux go build -a -o .

#暴露端口
EXPOSE 9010
#最终运行docker的命令
# CMD [ "/bin/sh"]
ENTRYPOINT  ["./gin-api"]