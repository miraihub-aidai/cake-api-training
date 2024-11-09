<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class GetArticles
{
    /**
     * GetArticles constructor
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
     * @return array<mixed> Decoded Articles data
     */
    public function __invoke(): array
    {
        return $this->articlesService->getArticles();
    }
}
