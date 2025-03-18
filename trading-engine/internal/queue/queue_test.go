package queue_test

import (
	"testing"

	"github.com/ferizoozoo/crypto-trading-simulator/trading-engine/internal/queue"
)

func TestEnqueue(t *testing.T) {
	q := queue.NewQueue()

	q.Enqueue("item1")

	if q.Size() != 1 {
		t.Errorf("Expected 1 item in the queue, but got %d", q.Size())
	}
}

func TestDequeue(t *testing.T) {
	q := queue.NewQueue()

	q.Enqueue("item1")
	q.Enqueue("item2")

	item1 := q.Dequeue()

	if item1 != "item1" {
		t.Errorf("Expected item1, but got %v", item1)
	}

	item2 := q.Dequeue()

	if item2 != "item2" {
		t.Errorf("Expected item2, but got %v", item2)
	}

	if q.Size() != 0 {
		t.Errorf("Expected 0 items in the queue, but got %d", q.Size())
	}
}
