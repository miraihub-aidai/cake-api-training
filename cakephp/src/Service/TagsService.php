<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Interface\TagsInterface;
use App\Model\Table\TagsTable;
use Cake\ORM\Locator\LocatorAwareTrait;
use RuntimeException;

class TagsService implements TagsInterface
{
    use LocatorAwareTrait;

    /**
     * TagsService constructor
     *
     * @param \App\Model\Table\TagsTable $tags
     */
    public function __construct(
        protected TagsTable $tags
    ) {
    }

    /**
     * Get tags
     *
     * @return array<mixed> Tags
     */
    public function getTags(): array
    {
        $tags = $this->tags->find()
            ->select(['id', 'title', 'created', 'modified'])
            ->all();

        return [
            'tags' => $tags->toArray(),
        ];
    }

    /**
     * Get tag by ID
     *
     * @param string $id Tag ID
     * @return array<mixed>|null Tags
     */
    public function getTagsById(string $id): ?array
    {
        $tags = $this->tags->find()
            ->select(['id', 'title', 'created', 'modified'])
            ->where(['id' => $id])
            ->first();

        return $tags ? $tags->toArray() : null;
    }

    /**
     * Post tags
     *
     * @param array<string, mixed> $data Tag data
     * @return array<mixed> Added tags
     * @throws \RuntimeException When unable to save tag
     */
    public function postTags(array $data): array
    {
        $tags = $this->tags->newEntity($data);
        if ($this->tags->save($tags)) {
            return $tags->toArray();
        }
        throw new RuntimeException('Unable to save tag');
    }

    /**
     * Put tags
     *
     * @param string $id Tag ID
     * @param array<string, mixed> $data Tag data
     * @return array<mixed> Updated tags
     * @throws \RuntimeException When unable to update tag
     */
    public function putTags(string $id, array $data): array
    {
        $tags = $this->tags->get($id);
        $tags = $this->tags->patchEntity($tags, $data);
        if ($this->tags->save($tags)) {
            return $tags->toArray();
        }
        throw new RuntimeException('Unable to update tag');
    }

    /**
     * Delete tags
     *
     * @param string $id Tag ID
     * @return bool Success
     * @throws \RuntimeException When unable to delete tag
     */
    public function deleteTags(string $id): bool
    {
        $tag = $this->tags->get($id);
        if ($this->tags->delete($tag)) {
            return true;
        }
        throw new RuntimeException('Unable to delete tag');
    }
}
