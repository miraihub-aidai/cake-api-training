<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Domain\UseCase\DeleteTags;
use App\Domain\UseCase\GetTags;
use App\Domain\UseCase\GetTagsById;
use App\Domain\UseCase\PostTags;
use App\Domain\UseCase\PutTags;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use JsonException;

/**
 * TagsController
 *
 * @property \App\Model\Table\TagsTable $Tags
 * @package App\Controller
 */
class TagsController extends AppController
{
    /**
     * Controller initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Get Tags Action
     *
     * @param \App\Domain\UseCase\GetTags $getTags
     * @return \Cake\Http\Response JSON Tags response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function getTags(GetTags $getTags)
    {
        $tags = $getTags();

        try {
            $jsonString = json_encode($tags, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Get Tag by ID Action
     *
     * @param \App\Domain\UseCase\GetTagsById $getTagsById
     * @param string $id Tag ID
     * @return \Cake\Http\Response JSON response
     * @throws \Cake\Http\Exception\NotFoundException When tag not found
     * @throws \Cake\Http\Exception\InternalErrorException When JSON encoding fails
     */
    public function getTagsById(GetTagsById $getTagsById, string $id)
    {
        try {
            $tag = $getTagsById($id);

            if (!$tag) {
                throw new NotFoundException('Tag not found');
            }

            $jsonString = json_encode($tag, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Post Tag Action
     *
     * @param \App\Domain\UseCase\PostTags $postTags
     * @return \Cake\Http\Response JSON Tag response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function postTags(PostTags $postTags)
    {
        $tag = $postTags($this->request->getData());

        try {
            $jsonString = json_encode($tag, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw new InternalErrorException('Unable to encode tag to JSON');
        }
    }

    /**
     * Put Tag Action
     *
     * @param \App\Domain\UseCase\PutTags $putTags
     * @param string $id Tag ID
     * @return \Cake\Http\Response JSON Tag response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function putTags(PutTags $putTags, string $id)
    {
        $tag = $putTags($id, $this->request->getData());

        try {
            $jsonString = json_encode($tag, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw new InternalErrorException('Unable to encode tag to JSON');
        }
    }

    /**
     * Delete Tag Action
     *
     * @param \App\Domain\UseCase\DeleteTags $deleteTags
     * @param string $id Tag ID
     * @return \Cake\Http\Response JSON response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function deleteTags(DeleteTags $deleteTags, string $id)
    {
        $result = $deleteTags($id);

        try {
            $jsonString = json_encode($result, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }
}
