<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Domain\UseCase\GetArticles;

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
     * @return \Cake\Http\Response JSON Articles response
     */
    public function getArticles(GetArticles $getArticles)
    {
        $articles = $getArticles();

        $jsonString = json_encode($articles, JSON_THROW_ON_ERROR);

        return $this->response
            ->withType('application/json')
            ->withStringBody($jsonString);
    }
}
