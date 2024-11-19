<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class PutArticles
{
    /**
     * PutArticle constructor
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
     * @param array<string, mixed> $data Article data
     * @return array<mixed> Edited article data
     */
    public function __invoke(string $id, array $data): array
    {
        return $this->articlesService->putArticles($id, $data);
    }
}
