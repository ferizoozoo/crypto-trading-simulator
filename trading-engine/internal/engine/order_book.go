package engine

type OrderBook struct {
	BuyOrders  *Queue
	SellOrders *Queue
}

func NewOrderBook() *OrderBook {
	return &OrderBook{
		BuyOrders:  NewQueue(),
		SellOrders: NewQueue(),
	}
}

func (ob *OrderBook) PlaceOrder(order *Order) error {
	if order.Type == BUY {
		ob.BuyOrders.Enqueue(order)
	}

	if order.Type == SELL {
		ob.SellOrders.Enqueue(order)
	}

	return nil
}
