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
     * @return void
     */
    public function getArticles(GetArticles $getArticles)
    {
        $articles = $getArticles();
        
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($articles));
    }
}
