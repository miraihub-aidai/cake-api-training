<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;

class DeleteArticle
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
     * invoke method
     *
     * @param string $id ArticleID
     * @return bool true: Success / false: Error
     */
    public function __invoke(string $id): bool
    {
        return $this->articlesService->deleteArticle($id);
    }
}
