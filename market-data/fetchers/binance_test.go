package fetchers

import (
	"encoding/json"
	"testing"
)

func TestFetchBinance(t *testing.T) {
	symbol := "btcusdt"
	binanceFetcher := NewBinanceFetcher()

	go binanceFetcher.Fetch(symbol)

	stream := <-binanceFetcher.stream
	var data map[string]interface{}

	if err := json.Unmarshal(stream, &data); err != nil {
		t.Errorf("Expected %v, got %v", nil, data)
	}

	expectedFields := []string{"Symbol", "Price"}

	for _, field := range expectedFields {
		if _, exists := data[field]; !exists {
			t.Errorf("Missing expected field: %s", field)
		}
	}
}
