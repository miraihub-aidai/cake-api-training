<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class DeleteArticles
{
    /**
     * DeleteArticle constructor
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
     * @return bool Success
     */
    public function __invoke(string $id): bool
    {
        return $this->articlesService->deleteArticles($id);
    }
}
