<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Domain\UseCase\DeleteArticles;
use App\Domain\UseCase\GetArticles;
use App\Domain\UseCase\GetArticlesById;
use App\Domain\UseCase\PostArticles;
use App\Domain\UseCase\PutArticles;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use JsonException;

/**
 * ArticlesController
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @property \App\Model\Table\TagsTable $Tags
 * @property \App\Model\Table\UsersTable $Users
 * @package App\Controller
 */
class ArticlesController extends AppController
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
     * Get Articles Action
     *
     * @param \App\Domain\UseCase\GetArticles $getArticles
     * @return \Cake\Http\Response JSON Articles response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function getArticles(GetArticles $getArticles)
    {
        $articles = $getArticles();

        try {
            $jsonString = json_encode($articles, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Get Article by ID Action
     *
     * @param \App\Domain\UseCase\GetArticlesById $getArticlesById
     * @param string $id Article ID
     * @return \Cake\Http\Response JSON response
     * @throws \Cake\Http\Exception\NotFoundException When article not found
     * @throws \Cake\Http\Exception\InternalErrorException When JSON encoding fails
     */
    public function getArticlesById(GetArticlesById $getArticlesById, string $id)
    {
        try {
            $article = $getArticlesById($id);

            if (!$article) {
                throw new NotFoundException('Article not found');
            }

            $jsonString = json_encode($article, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Post Article Action
     *
     * @param \App\Domain\UseCase\PostArticles $postArticles
     * @return \Cake\Http\Response JSON Article response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function PostArticles(PostArticles $postArticles)
    {
        $article = $postArticles($this->request->getData());

        try {
            $jsonString = json_encode($article, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw new InternalErrorException('Unable to encode article to JSON');
        }
    }

    /**
     * Put Article Action
     *
     * @param \App\Domain\UseCase\PutArticles $putArticles
     * @param string $id Article ID
     * @return \Cake\Http\Response JSON Article response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function putArticles(PutArticles $putArticles, string $id)
    {
        $article = $putArticles($id, $this->request->getData());

        try {
            $jsonString = json_encode($article, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw new InternalErrorException('Unable to encode article to JSON');
        }
    }

    /**
     * Delete Article Action
     *
     * @param \App\Domain\UseCase\DeleteArticles $deleteArticles
     * @param string $id Article ID
     * @return \Cake\Http\Response JSON response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function deleteArticles(DeleteArticles $deleteArticles, string $id)
    {
        $result = $deleteArticles($id);

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
