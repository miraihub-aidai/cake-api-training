<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\TagsInterface;

class DeleteTags
{
    /**
     * DeleteTags constructor
     *
     * @param \App\Domain\Interface\TagsInterface $tagsService
     */
    public function __construct(
        protected TagsInterface $tagsService
    ) {
    }

    /**
     * Invoke method
     *
     * @param string $id Tag ID
     * @return bool Success
     */
    public function __invoke(string $id): bool
    {
        return $this->tagsService->deleteTags($id);
    }
}
