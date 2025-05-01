package api

import (
	"encoding/json"
	"net/http"
	"testing"

	"github.com/ferizoozoo/crypto-trading-simulator/market-data/fetchers"
	"github.com/gorilla/websocket"
)

func TestFetchingMarketData(t *testing.T) {
	mux := http.NewServeMux()
	mux.HandleFunc("/data", Handler)

	server := http.Server{Addr: ":8080", Handler: mux}
	go server.ListenAndServe()

	// TODO: open websocket connection to the server
	conn, _, err := websocket.DefaultDialer.Dial("ws://localhost:8080/data?fetcherType=0&symbol=btcusdt", nil)
	if err != nil {
		t.Errorf("Expected %v, got %v", nil, err)
	}

	defer conn.Close()

	for i := 0; i < 10; i++ {
		_, msg, err := conn.ReadMessage()
		if err != nil {
			t.Errorf("Expected %v, got %v", nil, err)
		}

		var data fetchers.MarketData
		if err = json.Unmarshal(msg, &data); err != nil {
			t.Errorf("Expected %v, got %v", nil, err)
		}
	}
}
