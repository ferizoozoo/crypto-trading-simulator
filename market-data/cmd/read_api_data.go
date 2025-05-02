package main

import (
	"net/http"

	"github.com/ferizoozoo/crypto-trading-simulator/market-data/api"
)

func main() {
	mux := http.NewServeMux()
	mux.HandleFunc("/data", api.Handler)

	server := http.Server{Addr: ":8080", Handler: mux}
	server.ListenAndServe()

	defer server.Close()
}
