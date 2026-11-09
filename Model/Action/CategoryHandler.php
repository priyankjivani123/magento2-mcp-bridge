<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;

class CategoryHandler
{
    private CategoryRepositoryInterface $categoryRepository;
    private CategoryFactory $categoryFactory;
    private Registry $registry;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryFactory $categoryFactory,
        Registry $registry
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryFactory = $categoryFactory;
        $this->registry = $registry;
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

    public function getCategoryTree(array $params): array
    {
        $rootId = (int)($params['root_id'] ?? 2);
        $root = $this->categoryRepository->get($rootId);

        $childIds = array_filter(explode(',', (string)$root->getChildren()));
        $children = [];

        foreach ($childIds as $id) {
            try {
                $child = $this->categoryRepository->get((int)$id);
                $children[] = [
                    'id' => (int)$child->getId(),
                    'name' => $child->getName(),
                    'is_active' => (bool)$child->getIsActive(),
                    'level' => (int)$child->getLevel(),
                    'product_count' => (int)$child->getProductCount()
                ];
            } catch (\Throwable $e) {
                // skip missing child
            }
        }

        return [
            'root_id' => $rootId,
            'root_name' => $root->getName(),
            'subcategories_count' => count($children),
            'children' => $children
        ];
    }

    public function createCategory(array $params, bool $dryRun = false): array
    {
        $name = trim((string)($params['name'] ?? ''));
        $parentId = (int)($params['parent_id'] ?? 2);
        $isActive = (bool)($params['is_active'] ?? true);

        if ($name === '') {
            throw new \InvalidArgumentException('Category name is required.');
        }

        if ($dryRun) {
            return [
                'dry_run' => true,
                'name' => $name,
                'parent_id' => $parentId,
                'is_active' => $isActive,
                'message' => 'Category creation previewed successfully.'
            ];
        }

        $category = $this->categoryFactory->create();
        $category->setName($name);
        $category->setParentId($parentId);
        $category->setIsActive($isActive);
        $category->setIncludeInMenu(true);

        $saved = $this->categoryRepository->save($category);

        return [
            'success' => true,
            'id' => (int)$saved->getId(),
            'name' => $saved->getName(),
            'parent_id' => (int)$saved->getParentId(),
            'is_active' => (bool)$saved->getIsActive()
        ];
    }
}
