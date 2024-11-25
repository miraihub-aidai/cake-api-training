<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\TagsInterface;

class GetTags
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
     * @return array<string, mixed> Array of tags
     */
    public function __invoke(): array
    {
        return $this->tagsService->getTags();
    }
}
