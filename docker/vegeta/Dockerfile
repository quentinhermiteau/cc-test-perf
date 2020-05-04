FROM golang:alpine

RUN apk add --update git
RUN go get -u github.com/tsenart/vegeta
RUN addgroup -g 1000 -S vegeta
RUN adduser -h /home/vegeta -g "" -s /bin/sh -G vegeta -S -D -u 1000 vegeta
