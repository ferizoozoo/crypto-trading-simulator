package queue_test

import (
	"testing"

	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/queue"
)

func TestPush(t *testing.T) {
	item1 := &queue.Item{1, 10}
	item2 := &queue.Item{2, 20}
	item3 := &queue.Item{3, 30}

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

	item1 := &queue.Item{10, 10}
	item2 := &queue.Item{2, 20}
	item3 := &queue.Item{3, 30}

	pq.Push(item1)
	pq.Push(item2)
	pq.Push(item3)

	poppedItem := pq.Pop().(*queue.Item)

	if isMaxHeap && poppedItem.Priority != 10 {
		t.Errorf("Expected priority 10, but got %d", poppedItem.Priority)
	}

	if !isMaxHeap && poppedItem.Priority != 2 {
		t.Errorf("Expected priority 2, but got %d", poppedItem.Priority)
	}
}
