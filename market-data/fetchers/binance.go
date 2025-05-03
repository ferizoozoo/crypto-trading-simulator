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
	stream chan []byte
	conn   *websocket.Conn
}

func NewBinanceFetcher() *BinanceFetcher {
	return &BinanceFetcher{stream: make(chan []byte)}
}

func (bf *BinanceFetcher) connect(symbol string) error {
	urlPath := fmt.Sprintf("/ws/%s@trade", symbol)
	u := url.URL{Scheme: "wss", Host: BinanceUrl, Path: urlPath}

	conn, _, err := websocket.DefaultDialer.Dial(u.String(), nil)
	if err != nil {
		return err
	}

	bf.url = u.String()
	bf.conn = conn
	return nil
}

func (bf *BinanceFetcher) Fetch(symbol string) {
	err := bf.connect(symbol)
	if err != nil {
		panic(err)
	}

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
				Symbol:    rawData["s"].(string),
				Price:     rawData["p"].(string),
				Timestamp: rawData["E"].(float64),
			}
			cleanedMessage, err := json.Marshal(cleanedData)

			if err != nil {
				panic(err)
			}

			bf.stream <- cleanedMessage
		}
	}
}

func (bf *BinanceFetcher) GetStream() chan []byte {
	return bf.stream
}
