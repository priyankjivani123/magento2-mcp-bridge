<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Registry;

class CustomerHandler
{
    private CustomerRepositoryInterface $customerRepository;
    private CustomerInterfaceFactory $customerFactory;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private Registry $registry;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterfaceFactory $customerFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Registry $registry
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->registry = $registry;
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

    public function createCustomer(array $params, bool $dryRun = false): array
    {
        $email = trim((string)($params['email'] ?? ''));
        $firstname = trim((string)($params['firstname'] ?? ''));
        $lastname = trim((string)($params['lastname'] ?? ''));
        $websiteId = (int)($params['website_id'] ?? 1);

        if ($email === '' || $firstname === '' || $lastname === '') {
            throw new \InvalidArgumentException('email, firstname, and lastname are required.');
        }

        if ($dryRun) {
            return [
                'dry_run' => true,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'website_id' => $websiteId,
                'message' => 'Customer creation previewed successfully.'
            ];
        }

        $customer = $this->customerFactory->create();
        $customer->setEmail($email);
        $customer->setFirstname($firstname);
        $customer->setLastname($lastname);
        $customer->setWebsiteId($websiteId);

        $saved = $this->customerRepository->save($customer);

        return [
            'success' => true,
            'id' => (int)$saved->getId(),
            'email' => $saved->getEmail(),
            'firstname' => $saved->getFirstname(),
            'lastname' => $saved->getLastname()
        ];
    }
}
