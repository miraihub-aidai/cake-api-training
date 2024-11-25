<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\TagsInterface;

class GetTagsById
{
    /**
     * @param \App\Domain\Interface\TagsInterface $tagsService
     */
    public function __construct(
        private TagsInterface $tagsService
    ) {
    }

    /**
     * Invoke
     *
     * @param string $id Tag ID
     * @return array<string, mixed>|null Array of tag data or null if not found
     */
    public function __invoke(string $id): ?array
    {
        return $this->tagsService->getTagsById($id);
    }
}
