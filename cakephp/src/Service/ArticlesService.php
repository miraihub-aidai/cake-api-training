<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Interface\ArticlesInterface;
use App\Model\Table\ArticlesTable;
use Cake\ORM\Locator\LocatorAwareTrait;

class ArticlesService implements ArticlesInterface
{
    use LocatorAwareTrait;

    /**
     * ArticlesService constructor
     *
     * @param \App\Model\Table\ArticlesTable $articles
     */
    public function __construct(
        protected ArticlesTable $articles
    ) {
    }

    /**
     * Get articles
     *
     * @return array<mixed> Articles
     */
    public function getArticles(): array
    {
        $articles = $this->articles->find()
            ->select(['id', 'title', 'body', 'created', 'modified'])
            ->all();

        return [
            'articles' => $articles->toArray(),
        ];
    }
}
