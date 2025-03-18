package queue

type Queue struct {
	items []interface{}
}

func NewQueue() *Queue {
	return &Queue{
		items: []interface{}{},
	}
}

func (q *Queue) Enqueue(item interface{}) {
	q.items = append(q.items, item)
}

func (q *Queue) Dequeue() interface{} {
	item := q.items[0]
	q.items = q.items[1:]

	return item
}

func (q *Queue) Size() int {
	return len(q.items)
}
