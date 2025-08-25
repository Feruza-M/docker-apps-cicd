FROM golang:1.22
WORKDIR /app
COPY . .
RUN go build -o go-app main.go
EXPOSE 8180
ENTRYPOINT ["./go-app"]

