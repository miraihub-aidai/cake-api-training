<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class PostArticles
{
    /**
     * postArticles constructor
     *
     * @param \App\Domain\Interface\ArticlesInterface $articlesService
     */
    public function __construct(
        protected ArticlesInterface $articlesService
    ) {
    }

    /**
     * Invoke method
     *
     * @param array<string, mixed> $data Article data
     * @return array<mixed> Added article data
     */
    public function __invoke(array $data): array
    {
        return $this->articlesService->postArticles($data);
    }
}
