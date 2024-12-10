<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class PatchArticle
{
    /**
     * PatchArticle constructor
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
     * @param array<mixed> $data Article Request Data
     * @return array<mixed> Decoded Articles data
     */
    public function __invoke(string $id, array $data): array
    {
        return $this->articlesService->patchArticle($id, $data);
    }
}
