<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CustomerHandler
{
    private CustomerRepositoryInterface $customerRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function listCustomers(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $limit = min((int)($params['limit'] ?? 20), 100);

        $this->searchCriteriaBuilder->setCurrentPage($page);
        $this->searchCriteriaBuilder->setPageSize($limit);

        $criteria = $this->searchCriteriaBuilder->create();
        $results = $this->customerRepository->getList($criteria);

        $customers = [];
        foreach ($results->getItems() as $cust) {
            $customers[] = [
                'id' => (int)$cust->getId(),
                'email' => $cust->getEmail(),
                'firstname' => $cust->getFirstname(),
                'lastname' => $cust->getLastname(),
                'group_id' => (int)$cust->getGroupId(),
                'website_id' => (int)$cust->getWebsiteId(),
                'created_at' => $cust->getCreatedAt()
            ];
        }

        return [
            'total_count' => $results->getTotalCount(),
            'page' => $page,
            'limit' => $limit,
            'customers' => $customers
        ];
    }

    public function getCustomer(array $params): array
    {
        $customerId = (int)($params['customer_id'] ?? 0);
        if (!$customerId) {
            throw new \InvalidArgumentException('customer_id is required.');
        }

        $cust = $this->customerRepository->getById($customerId);

        return [
            'id' => (int)$cust->getId(),
            'email' => $cust->getEmail(),
            'firstname' => $cust->getFirstname(),
            'lastname' => $cust->getLastname(),
            'group_id' => (int)$cust->getGroupId(),
            'website_id' => (int)$cust->getWebsiteId(),
            'created_at' => $cust->getCreatedAt(),
            'updated_at' => $cust->getUpdatedAt()
        ];
    }
}
