package engine

type Order struct {
	UserID int
	Size   int
	Price  float64
	Type   OrderType
}

type OrderType int

const (
	BUY OrderType = iota + 1
	SELL
)
