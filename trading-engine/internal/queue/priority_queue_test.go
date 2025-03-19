package queue_test

import (
	"testing"

	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/queue"
)

func TestPush(t *testing.T) {
	item1 := &queue.Item{2, 2, 10}
	item2 := &queue.Item{2, 3, 20}
	item3 := &queue.Item{3, 4, 30}

	pq := queue.NewPriorityQueue(true)

	pq.Push(item1)
	pq.Push(item2)
	pq.Push(item3)

	if pq.Len() != 3 {
		t.Errorf("Expected 3 items in the queue, but got %d", pq.Len())
	}
}

func TestPop(t *testing.T) {
	isMaxHeap := true
	pq := queue.NewPriorityQueue(isMaxHeap)

	item1 := &queue.Item{10, 4, 10}
	item2 := &queue.Item{10, 5, 20}
	item3 := &queue.Item{3, 2, 30}

	pq.Push(item1)
	pq.Push(item2)
	pq.Push(item3)

	poppedItem := pq.Pop().(*queue.Item)

	if isMaxHeap && poppedItem.PrimaryRank != 10 && poppedItem.SecondaryRank != 4 {
		t.Errorf("Expected primary rank 10 and secondary rank 4, but got %d and %d", poppedItem.PrimaryRank, poppedItem.SecondaryRank)
	}

	if !isMaxHeap && poppedItem.PrimaryRank != 3 && poppedItem.SecondaryRank != 2 {
		t.Errorf("Expected primary rank 3 and secondary rank 2, but got %d and %d", poppedItem.PrimaryRank, poppedItem.SecondaryRank)
	}
}
