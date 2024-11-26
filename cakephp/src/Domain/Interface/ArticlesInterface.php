<?php
declare(strict_types=1);

namespace App\Domain\Interface;

interface ArticlesInterface
{
    /**
     * Get articles
     *
     * @return array<mixed> Articles
     */
    public function getArticles(): array;

    /**
     * Get article by ID
     *
     * @param string $id Article ID
     * @return array<mixed>|null Article
     */
    public function getArticlesById(string $id): ?array;

    /**
     * Post articles
     *
     * @param array<string, mixed> $data Article data
     * @return array<mixed> Added articles
     * @throws \RuntimeException When unable to save article
     */
    public function postArticles(array $data): array;

    /**
     * Put articles
     *
     * @param string $id Article ID
     * @param array<string, mixed> $data Article data
     * @return array<mixed> Updated articles
     * @throws \RuntimeException When unable to update article
     */
    public function putArticles(string $id, array $data): array;

    /**
     * Delete articles
     *
     * @param string $id Article ID
     * @return bool Success
     * @throws \RuntimeException When unable to delete article
     */
    public function deleteArticles(string $id): bool;
}
