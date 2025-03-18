package engine

import "github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/queue"

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

func (ob *OrderBook) PlaceOrder(order *Order) error {
	item := &queue.Item{
		Priority: int(order.Price),
		Item:     order,
	}

	if order.Type == BUY {
		ob.BuyOrders.Push(item)
	}

	if order.Type == SELL {
		ob.SellOrders.Push(item)
	}

	return nil
}
