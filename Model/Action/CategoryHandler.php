<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class CategoryHandler
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategory(array $params): array
    {
        $categoryId = (int)($params['category_id'] ?? 0);
        if (!$categoryId) {
            throw new \InvalidArgumentException('category_id is required.');
        }

        $cat = $this->categoryRepository->get($categoryId);

        return [
            'id' => (int)$cat->getId(),
            'name' => $cat->getName(),
            'parent_id' => (int)$cat->getParentId(),
            'is_active' => (bool)$cat->getIsActive(),
            'position' => (int)$cat->getPosition(),
            'level' => (int)$cat->getLevel(),
            'product_count' => (int)$cat->getProductCount()
        ];
    }
}
