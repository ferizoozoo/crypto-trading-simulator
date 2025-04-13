package fetchers

type Fetcher interface {
	Fetch()
}

type MarketData struct {
	Symbol string
	Price  string
}
