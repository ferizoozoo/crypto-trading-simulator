package engine

type OrderBook struct {
	BuyOrders []*Order
}

func NewOrderBook() *OrderBook {
	return &OrderBook{BuyOrders: []*Order{}}
}

func (ob *OrderBook) PlaceOrder(order *Order) error {
	ob.BuyOrders = append(ob.BuyOrders, order)

	return nil
}
