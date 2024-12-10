<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class CreateArticle
{
    /**
     * CreateArticle constructor
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
     * @param array<mixed> $data Article Request Data
     * @return array<mixed> Decoded Articles data
     */
    public function __invoke(array $data): array
    {
        return $this->articlesService->createArticle($data);
    }
}
