<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Interface\ArticlesInterface;
use App\Model\Table\ArticlesTable;
use Cake\ORM\Locator\LocatorAwareTrait;
use RuntimeException;

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

    /**
     * Get article by ID
     *
     * @param string $id Article ID
     * @return array<mixed>|null Articles
     */
    public function getArticlesById(string $id): ?array
    {
        $articles = $this->articles->find()
            ->select(['id', 'title', 'body', 'created', 'modified'])
            ->where(['id' => $id])
            ->first();

        return $articles ? $articles->toArray() : null;
    }

    /**
     * Post articles
     *
     * @param array<string, mixed> $data Article data
     * @return array<mixed> Added articles
     * @throws \RuntimeException When unable to save article
     */
    public function postArticles(array $data): array
    {
        $articles = $this->articles->newEntity($data);
        if ($this->articles->save($articles)) {
            return $articles->toArray();
        }
        throw new RuntimeException('Unable to save article');
    }

    /**
     * Put articles
     *
     * @param string $id Article ID
     * @param array<string, mixed> $data Article data
     * @return array<mixed> Updated articles
     * @throws \RuntimeException When unable to update article
     */
    public function putArticles(string $id, array $data): array
    {
        $articles = $this->articles->get($id);
        $articles = $this->articles->patchEntity($articles, $data);
        if ($this->articles->save($articles)) {
            return $articles->toArray();
        }
        throw new RuntimeException('Unable to update article');
    }

    /**
     * Delete articles
     *
     * @param string $id Article ID
     * @return bool Success
     * @throws \RuntimeException When unable to delete article
     */
    public function deleteArticles(string $id): bool
    {
        $article = $this->articles->get($id);
        if ($this->articles->delete($article)) {
            return true;
        }
        throw new RuntimeException('Unable to delete article');
    }
}
