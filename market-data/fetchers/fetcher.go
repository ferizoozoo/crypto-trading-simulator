package fetchers

const (
	BINANCE FetcherType = iota
)

type FetcherType int

type Fetcher interface {
	Fetch(string)
	GetStream() chan []byte
}

type MarketData struct {
	Symbol string
	Price  string
}

func GetFetcher(fetcherType FetcherType) Fetcher {
	switch fetcherType {
	case BINANCE:
		return NewBinanceFetcher()
	default:
		panic("Wrong fetcherType provided")
	}
}
