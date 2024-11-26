<?php
declare(strict_types=1);

namespace App\Domain\Interface;

interface TagsInterface
{
    /**
     * Get tags
     *
     * @return array<mixed> Tags
     */
    public function getTags(): array;

    /**
     * Get tag by ID
     *
     * @param string $id Tag ID
     * @return array<mixed>|null Tag
     */
    public function getTagsById(string $id): ?array;

    /**
     * Post tags
     *
     * @param array<string, mixed> $data Tag data
     * @return array<mixed> Added tags
     * @throws \RuntimeException When unable to save tag
     */
    public function postTags(array $data): array;

    /**
     * Put tags
     *
     * @param string $id Tag ID
     * @param array<string, mixed> $data Tag data
     * @return array<mixed> Updated tags
     * @throws \RuntimeException When unable to update tag
     */
    public function putTags(string $id, array $data): array;

    /**
     * Delete tags
     *
     * @param string $id Tag ID
     * @return bool Success
     * @throws \RuntimeException When unable to delete tag
     */
    public function deleteTags(string $id): bool;
}
