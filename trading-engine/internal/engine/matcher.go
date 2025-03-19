package engine

import (
	"time"

	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/queue"
)

type Matcher struct {
	ob *OrderBook
}

func NewMatcher(ob *OrderBook) *Matcher {
	return &Matcher{ob: ob}
}

func (m *Matcher) calculateTrade(buyOrder *Order, sellOrder *Order) *Trade {
	var trade *Trade

	if buyOrder.Price < sellOrder.Price {
		return nil
	}

	if buyOrder.Size < sellOrder.Size {
		// TODO: think about it: should the real orders be sent, or the updated?
		sellOrder.Size -= buyOrder.Size

		trade = &Trade{
			buyOrder,
			sellOrder,
			sellOrder.Price,
			buyOrder.Size,
			time.Now(),
		}

		buyOrder.Size = 0
	}

	if buyOrder.Size >= sellOrder.Size {
		// TODO: think about it: should the real orders be sent, or the updated?
		buyOrder.Size -= sellOrder.Size

		trade = &Trade{
			buyOrder,
			sellOrder,
			sellOrder.Price,
			sellOrder.Size,
			time.Now(),
		}

		sellOrder.Size = 0
	}

	return trade
}

func (m *Matcher) MatchOrders() (*Trade, error) {
	if m.ob.BuyOrders.Size() == 0 || m.ob.SellOrders.Size() == 0 {
		return nil, nil
	}

	buyOrder := m.ob.BuyOrders.Pop().(*queue.Item).Item.(*Order)
	sellOrder := m.ob.SellOrders.Pop().(*queue.Item).Item.(*Order)

	trade := m.calculateTrade(buyOrder, sellOrder)

	if trade != nil && trade.BuyOrder.Size > 0 && trade.SellOrder.Size > 0 {
		m.ob.BuyOrders.Push(&queue.Item{PrimaryRank: int(trade.BuyOrder.Price), SecondaryRank: int(trade.BuyOrder.Timestamp), Item: trade.BuyOrder})
		m.ob.SellOrders.Push(&queue.Item{PrimaryRank: int(trade.SellOrder.Price), SecondaryRank: int(trade.SellOrder.Timestamp), Item: trade.SellOrder})
	}

	return trade, nil
}
