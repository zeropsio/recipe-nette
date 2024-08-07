<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use Nette;
use Nette\Database\Table\ActiveRow;


/**
 * Facade for handling operations related to posts.
 */
final class PostFacade
{
    /**
     * Dependency injection of the database.
     */
    public function __construct(
        private Nette\Database\Explorer $database,
    )
    {
    }


    /**
     * Fetches all articles that were created before the current date.
     * Articles are ordered by their creation date in descending order.
     */
    public function getPublicArticles()
    {
        return $this->database
            ->table('posts')
            ->where('created_at < ', new DateTime)
            ->order('created_at DESC');
    }

    public function getById(int $id): ActiveRow|null
    {
        return $this->database
            ->table('posts')
            ->get($id);
    }

}
