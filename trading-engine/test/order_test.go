package test

import (
	"testing"

	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/engine"
)

func setUp() *engine.OrderBook {
	return engine.NewOrderBook()
}

func TestBuyOrder(t *testing.T) {
	ob := setUp()

	order := engine.Order{
		UserID: 1,
		Price:  100,
		Size:   10,
		Type:   engine.BUY,
	}

	err := ob.PlaceOrder(&order)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	if len(ob.BuyOrders) == 0 {
		t.Errorf("Expected at least one buy order, but got none")
	}
}
