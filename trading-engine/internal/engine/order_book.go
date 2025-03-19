package engine

import (
	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/queue"
)

type OrderBook struct {
	BuyOrders  *queue.PriorityQueue
	SellOrders *queue.PriorityQueue
}

func NewOrderBook() *OrderBook {
	return &OrderBook{
		BuyOrders:  queue.NewPriorityQueue(true),
		SellOrders: queue.NewPriorityQueue(false),
	}
}

func (ob *OrderBook) PlaceOrder(order *Order) (*Trade, error) {
	// TODO: maybe define an interface for Item, so if Order implements it, we don't need this
	item := &queue.Item{
		PrimaryRank:   int(order.Price),
		SecondaryRank: int(order.Timestamp),
		Item:          order,
	}

	if order.Type == BUY {
		ob.BuyOrders.Push(item)
	}

	if order.Type == SELL {
		ob.SellOrders.Push(item)
	}

	// TODO: this should be replaced with the pub/sub pattern
	trade, err := NewMatcher(ob).MatchOrders()

	return trade, err
}
