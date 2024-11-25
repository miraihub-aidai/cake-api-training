<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\TagsInterface;

class PostTags
{
    /**
     * postTags constructor
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
     * @param array<string, mixed> $data Tag data
     * @return array<mixed> Added tag data
     */
    public function __invoke(array $data): array
    {
        return $this->tagsService->postTags($data);
    }
}
