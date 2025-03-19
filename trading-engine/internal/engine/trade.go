package engine

import (
	"time"
)

type Trade struct {
	BuyOrder  *Order
	SellOrder *Order
	Price     float64
	Quantity  int
	time      time.Time
}
