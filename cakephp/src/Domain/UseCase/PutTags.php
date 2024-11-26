<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\TagsInterface;

class PutTags
{
    /**
     * PutTags constructor
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
     * @param array<string, mixed> $data Tag data
     * @return array<mixed> Edited tag data
     */
    public function __invoke(string $id, array $data): array
    {
        return $this->tagsService->putTags($id, $data);
    }
}
