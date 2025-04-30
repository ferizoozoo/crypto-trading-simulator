package fetchers

type Fetcher interface {
	Fetch(string)
}

type MarketData struct {
	Symbol string
	Price  string
}
