package queue

type Item struct {
	PrimaryRank   int
	SecondaryRank int
	Item          interface{}
}

type PriorityQueue struct {
	items     []*Item
	isMaxHeap bool
}

func NewPriorityQueue(isMaxHeap bool) *PriorityQueue {
	return &PriorityQueue{
		items:     []*Item{},
		isMaxHeap: isMaxHeap,
	}
}

func (pq *PriorityQueue) Len() int {
	return len(pq.items)
}

func (pq *PriorityQueue) Less(i, j int) bool {
	if pq.isMaxHeap {
		return pq.items[i].PrimaryRank > pq.items[j].PrimaryRank ||
			(pq.items[i].PrimaryRank == pq.items[j].PrimaryRank && pq.items[i].SecondaryRank < pq.items[j].SecondaryRank)
	}
	return pq.items[i].PrimaryRank < pq.items[j].PrimaryRank ||
		(pq.items[i].PrimaryRank == pq.items[j].PrimaryRank && pq.items[i].SecondaryRank < pq.items[j].SecondaryRank)
}

func (pq *PriorityQueue) Size() int {
	return len(pq.items)
}

func (pq *PriorityQueue) Push(item interface{}) {
	pq.items = append(pq.items, item.(*Item))

	if len(pq.items) == 1 {
		return
	}

	index := len(pq.items) - 1

	for index > 0 {
		if pq.Less(index, index/2) {
			pq.items[index], pq.items[index/2] = pq.items[index/2], pq.items[index]
		}
		index /= 2
	}
}

func (pq *PriorityQueue) Pop() interface{} {
	if len(pq.items) == 0 {
		return nil
	}

	if len(pq.items) == 1 {
		item := (pq.items)[0]
		pq.items = nil
		return item
	}

	item := (pq.items)[0]
	(pq.items)[0] = (pq.items)[len(pq.items)-1]
	pq.items = (pq.items)[:len(pq.items)-1]

	index := 0

	for index < (len(pq.items)-2)/2 && pq.Less(index, index*2+1) {
		if index*2+2 < len(pq.items) && pq.Less(index*2+2, index*2+1) {
			(pq.items)[index], (pq.items)[index*2+2] = (pq.items)[index*2+2], (pq.items)[index]
			index = index*2 + 2
		} else {
			(pq.items)[index], (pq.items)[index*2+1] = (pq.items)[index*2+1], (pq.items)[index]
			index = index*2 + 1
		}
	}

	return item
}
