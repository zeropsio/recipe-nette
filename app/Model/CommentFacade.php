<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Table\ActiveRow;


/**
 * Facade for handling operations related to posts.
 */
final class CommentFacade
{
    /**
     * Dependency injection of the database.
     */
    public function __construct(
        private Nette\Database\Explorer $database,
    )
    {
    }

    public function insert(int $postId, string $content, string $name, ?string $email): ActiveRow|array|int|bool
    {
        return $this->database->table('comments')->insert([
            'post_id' => $postId,
            'name'    => $name,
            'email'   => $email,
            'content' => $content,
        ]);
    }
}
