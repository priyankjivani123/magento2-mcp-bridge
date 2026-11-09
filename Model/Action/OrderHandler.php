<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class OrderHandler
{
    private OrderRepositoryInterface $orderRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function listOrders(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $limit = min((int)($params['limit'] ?? 20), 100);

        $this->searchCriteriaBuilder->setCurrentPage($page);
        $this->searchCriteriaBuilder->setPageSize($limit);

        if (!empty($params['status'])) {
            $this->searchCriteriaBuilder->addFilter('status', $params['status']);
        }
        if (!empty($params['customer_email'])) {
            $this->searchCriteriaBuilder->addFilter('customer_email', $params['customer_email']);
        }

        $criteria = $this->searchCriteriaBuilder->create();
        $results = $this->orderRepository->getList($criteria);

        $orders = [];
        foreach ($results->getItems() as $order) {
            $orders[] = [
                'entity_id' => (int)$order->getEntityId(),
                'increment_id' => $order->getIncrementId(),
                'status' => $order->getStatus(),
                'state' => $order->getState(),
                'grand_total' => (float)$order->getGrandTotal(),
                'currency' => $order->getOrderCurrencyCode(),
                'total_qty_ordered' => (float)$order->getTotalQtyOrdered(),
                'customer_email' => $order->getCustomerEmail(),
                'customer_firstname' => $order->getCustomerFirstname(),
                'customer_lastname' => $order->getCustomerLastname(),
                'created_at' => $order->getCreatedAt()
            ];
        }

        return [
            'total_count' => $results->getTotalCount(),
            'page' => $page,
            'limit' => $limit,
            'orders' => $orders
        ];
    }

    public function getOrder(array $params): array
    {
        $orderId = $params['order_id'] ?? $params['increment_id'] ?? null;
        if (!$orderId) {
            throw new \InvalidArgumentException('order_id or increment_id is required.');
        }

        if (is_numeric($orderId)) {
            $order = $this->orderRepository->get((int)$orderId);
        } else {
            $this->searchCriteriaBuilder->addFilter('increment_id', (string)$orderId);
            $list = $this->orderRepository->getList($this->searchCriteriaBuilder->create());
            $items = $list->getItems();
            if (empty($items)) {
                throw new \InvalidArgumentException("Order not found with increment ID: $orderId");
            }
            $order = reset($items);
        }

        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $items[] = [
                'item_id' => (int)$item->getItemId(),
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'qty_ordered' => (float)$item->getQtyOrdered(),
                'price' => (float)$item->getPrice(),
                'row_total' => (float)$item->getRowTotal()
            ];
        }

        $history = [];
        foreach ($order->getStatusHistories() as $comment) {
            $history[] = [
                'comment_id' => (int)$comment->getEntityId(),
                'status' => $comment->getStatus(),
                'comment' => $comment->getComment(),
                'is_customer_notified' => (int)$comment->getIsCustomerNotified(),
                'created_at' => $comment->getCreatedAt()
            ];
        }

        return [
            'entity_id' => (int)$order->getEntityId(),
            'increment_id' => $order->getIncrementId(),
            'status' => $order->getStatus(),
            'state' => $order->getState(),
            'grand_total' => (float)$order->getGrandTotal(),
            'subtotal' => (float)$order->getSubtotal(),
            'shipping_amount' => (float)$order->getShippingAmount(),
            'tax_amount' => (float)$order->getTaxAmount(),
            'currency' => $order->getOrderCurrencyCode(),
            'customer_email' => $order->getCustomerEmail(),
            'items' => $items,
            'status_history' => $history,
            'created_at' => $order->getCreatedAt()
        ];
    }

    public function addComment(array $params): array
    {
        $orderId = (int)($params['order_id'] ?? 0);
        $commentText = (string)($params['comment'] ?? '');
        $status = $params['status'] ?? null;
        $notify = (bool)($params['notify'] ?? false);
        $visibleOnFront = (bool)($params['visible_on_front'] ?? false);

        if (!$orderId || $commentText === '') {
            throw new \InvalidArgumentException('order_id and comment are required.');
        }

        $order = $this->orderRepository->get($orderId);
        $history = $order->addCommentToStatusHistory($commentText, $status, $visibleOnFront);
        $history->setIsCustomerNotified($notify ? 1 : 0);
        $this->orderRepository->save($order);

        return [
            'success' => true,
            'order_id' => $orderId,
            'comment' => $commentText,
            'status' => $order->getStatus(),
            'customer_notified' => $notify
        ];
    }
}
