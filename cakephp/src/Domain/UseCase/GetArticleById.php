<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class GetArticleById
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
     * invoke method
     *
     * @param string $id ArticleID
     * @return array<mixed> Decoded Article data
     */
    public function __invoke(string $id): array
    {
        return $this->articlesService->getArticleById($id);
    }
}
