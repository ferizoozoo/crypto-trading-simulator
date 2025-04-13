package fetchers

import (
	"encoding/json"
	"fmt"
	"net/url"

	"github.com/gorilla/websocket"
)

const BinanceUrl = "stream.binance.com:9443"

type BinanceFetcher struct {
	url    string
	symbol string
	stream chan []byte
	conn   *websocket.Conn
}

func NewBinanceFetcher(symbol string) *BinanceFetcher {
	u := url.URL{Scheme: "wss", Host: BinanceUrl, Path: fmt.Sprintf("/ws/%s@trade", symbol)}
	conn, _, err := websocket.DefaultDialer.Dial(u.String(), nil)
	if err != nil {
		panic(err)
	}
	return &BinanceFetcher{url: u.String(), symbol: symbol, stream: make(chan []byte), conn: conn}
}

func (bf *BinanceFetcher) Fetch() {
	defer bf.conn.Close()

	for {
		_, message, err := bf.conn.ReadMessage()

		if err != nil {
			panic(err)
		}

		if message != nil {
			var rawData map[string]interface{}

			if err := json.Unmarshal(message, &rawData); err != nil {
				panic(err)
			}

			cleanedData := MarketData{
				Symbol: rawData["s"].(string),
				Price:  rawData["p"].(string),
			}
			cleanedMessage, err := json.Marshal(cleanedData)

			if err != nil {
				panic(err)
			}

			bf.stream <- cleanedMessage
		}
	}
}
