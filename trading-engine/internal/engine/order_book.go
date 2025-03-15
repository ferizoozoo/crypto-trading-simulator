package engine

type OrderBook struct {
	BuyOrders *Queue
}

func NewOrderBook() *OrderBook {
	return &OrderBook{BuyOrders: NewQueue()}
}

func (ob *OrderBook) PlaceOrder(order *Order) error {
	ob.BuyOrders.Enqueue(order)
	return nil
}
