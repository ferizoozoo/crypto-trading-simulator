package queue

type Item struct {
	Priority int
	Item     interface{}
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
		return pq.items[i].Priority > pq.items[j].Priority
	}
	return pq.items[i].Priority < pq.items[j].Priority
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
		pq.items = (pq.items)[:0]
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
