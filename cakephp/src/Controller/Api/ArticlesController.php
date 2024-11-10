<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Domain\UseCase\GetArticles;
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
}
