package test

import (
	"testing"
	"time"

	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/engine"
)

func TestBuyOrderSizeLessThanSellOrderSize(t *testing.T) {
	ob := setUp()

	buyOrder := engine.Order{
		UserID: 1,
		Price:  100,
		Size:   9,
		Type:   engine.BUY,
	}

	sellOrder := engine.Order{
		UserID: 2,
		Price:  100,
		Size:   10,
		Type:   engine.SELL,
	}

	var trade *engine.Trade

	_, err := ob.PlaceOrder(&buyOrder)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	trade, err = ob.PlaceOrder(&sellOrder)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	expectedTrade := &engine.Trade{
		BuyOrder:  &buyOrder,
		SellOrder: &sellOrder,
		Price:     100,
		Quantity:  9,
	}

	if trade.Price != expectedTrade.Price {
		t.Errorf("Expected price %f, but got %f", expectedTrade.Price, trade.Price)
	}

	if trade.Quantity != expectedTrade.Quantity {
		t.Errorf("Expected quantity %d, but got %d", expectedTrade.Quantity, trade.Quantity)
	}
}

func TestBuyOrderSizeMoreThanSellOrderSize(t *testing.T) {
	ob := setUp()

	buyOrder := engine.Order{
		UserID: 1,
		Price:  100,
		Size:   11,
		Type:   engine.BUY,
	}

	sellOrder := engine.Order{
		UserID: 2,
		Price:  100,
		Size:   10,
		Type:   engine.SELL,
	}

	var trade *engine.Trade

	_, err := ob.PlaceOrder(&buyOrder)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	trade, err = ob.PlaceOrder(&sellOrder)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	expectedTrade := &engine.Trade{
		BuyOrder:  &buyOrder,
		SellOrder: &sellOrder,
		Price:     100,
		Quantity:  10,
	}

	if trade.Price != expectedTrade.Price {
		t.Errorf("Expected price %f, but got %f", expectedTrade.Price, trade.Price)
	}

	if trade.Quantity != expectedTrade.Quantity {
		t.Errorf("Expected quantity %d, but got %d", expectedTrade.Quantity, trade.Quantity)
	}
}

func TestMoreThanOneBuyOrders(t *testing.T) {
	ob := setUp()

	buyOrder1 := engine.Order{
		UserID:    1,
		Price:     105,
		Size:      11,
		Type:      engine.BUY,
		Timestamp: int(time.Now().Unix()),
	}

	buyOrder2 := engine.Order{
		UserID:    1,
		Price:     101,
		Size:      5,
		Type:      engine.BUY,
		Timestamp: int(time.Now().Unix()),
	}

	sellOrder := engine.Order{
		UserID:    2,
		Price:     100,
		Size:      10,
		Type:      engine.SELL,
		Timestamp: int(time.Now().Unix()),
	}

	var trade *engine.Trade

	_, err := ob.PlaceOrder(&buyOrder1)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	_, err = ob.PlaceOrder(&buyOrder2)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	trade, err = ob.PlaceOrder(&sellOrder)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	expectedTrade := &engine.Trade{
		BuyOrder:  &buyOrder1,
		SellOrder: &sellOrder,
		Price:     100,
		Quantity:  10,
	}

	if trade.Price != expectedTrade.Price {
		t.Errorf("Expected price %f, but got %f", expectedTrade.Price, trade.Price)
	}

	if trade.Quantity != expectedTrade.Quantity {
		t.Errorf("Expected quantity %d, but got %d", expectedTrade.Quantity, trade.Quantity)
	}
}

func TestBuyOrdersWithSamePrice(t *testing.T) {
	ob := setUp()

	buyOrder1 := engine.Order{
		UserID:    1,
		Price:     101,
		Size:      11,
		Type:      engine.BUY,
		Timestamp: int(time.Now().Unix()),
	}

	buyOrder2 := engine.Order{
		UserID:    2,
		Price:     101,
		Size:      5,
		Type:      engine.BUY,
		Timestamp: int(time.Now().Unix()),
	}

	sellOrder := engine.Order{
		UserID:    3,
		Price:     100,
		Size:      10,
		Type:      engine.SELL,
		Timestamp: int(time.Now().Unix()),
	}

	var trade *engine.Trade

	_, err := ob.PlaceOrder(&buyOrder1)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	_, err = ob.PlaceOrder(&buyOrder2)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	trade, err = ob.PlaceOrder(&sellOrder)

	if err != nil {
		t.Errorf("Expected no error, but got: %v", err)
	}

	expectedTrade := &engine.Trade{
		BuyOrder:  &buyOrder1,
		SellOrder: &sellOrder,
		Price:     100,
		Quantity:  10,
	}

	if trade.Price != expectedTrade.Price {
		t.Errorf("Expected price %f, but got %f", expectedTrade.Price, trade.Price)
	}

	if trade.Quantity != expectedTrade.Quantity {
		t.Errorf("Expected quantity %d, but got %d", expectedTrade.Quantity, trade.Quantity)
	}
}
