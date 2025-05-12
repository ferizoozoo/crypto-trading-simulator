package api

import (
	"net/http"
	"strconv"

	"github.com/ferizoozoo/crypto-trading-simulator/market-data/fetchers"
	"github.com/gorilla/websocket"
)

func Handler(w http.ResponseWriter, r *http.Request) {
	upgrader := websocket.Upgrader{
		CheckOrigin: func(r *http.Request) bool {
			return true
		},
	}
	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		panic(err)
	}

	for {
		fetcherTypeString := r.URL.Query().Get("fetcherType")
		symbol := r.URL.Query().Get("symbol")

		if fetcherTypeString == "" || symbol == "" {
			panic("Missing fetcherType or symbol")
		}

		fetcherTypeInt, err := strconv.Atoi(fetcherTypeString)
		if err != nil {
			panic(err)
		}

		fetcherType := fetchers.FetcherType(fetcherTypeInt)
		stream := fetch(fetcherType, symbol)

		message := <-stream

		conn.WriteMessage(websocket.BinaryMessage, message)
	}
}

func fetch(fetcherType fetchers.FetcherType, symbol string) chan []byte {
	fetcher := fetchers.GetFetcher(fetcherType)
	go fetcher.Fetch(symbol)
	return fetcher.GetStream()
}
