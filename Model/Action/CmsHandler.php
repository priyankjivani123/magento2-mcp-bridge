<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Api\BlockRepositoryInterface;

class CmsHandler
{
    private PageRepositoryInterface $pageRepository;
    private BlockRepositoryInterface $blockRepository;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        BlockRepositoryInterface $blockRepository
    ) {
        $this->pageRepository = $pageRepository;
        $this->blockRepository = $blockRepository;
    }

    public function getCmsPage(array $params): array
    {
        $pageId = (int)($params['page_id'] ?? 0);
        if (!$pageId) {
            throw new \InvalidArgumentException('page_id is required.');
        }

        $page = $this->pageRepository->getById($pageId);

        return [
            'id' => (int)$page->getId(),
            'identifier' => $page->getIdentifier(),
            'title' => $page->getTitle(),
            'is_active' => (bool)$page->isActive(),
            'content_heading' => $page->getContentHeading()
        ];
    }

    public function getCmsBlock(array $params): array
    {
        $blockId = (int)($params['block_id'] ?? 0);
        if (!$blockId) {
            throw new \InvalidArgumentException('block_id is required.');
        }

        $block = $this->blockRepository->getById($blockId);

        return [
            'id' => (int)$block->getId(),
            'identifier' => $block->getIdentifier(),
            'title' => $block->getTitle(),
            'is_active' => (bool)$block->isActive()
        ];
    }
}
