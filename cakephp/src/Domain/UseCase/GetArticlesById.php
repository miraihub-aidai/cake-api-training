<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class GetArticlesById
{
    /**
     * GetArticleById constructor
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
     * @param string $id Article ID
     * @return array<mixed>|null Article data
     */
    public function __invoke(string $id): ?array
    {
        return $this->articlesService->getArticlesById($id);
    }
}
