<?php

declare(strict_types=1);

namespace App\UI\Post;

use App\Model\CommentFacade;
use App\Model\PostFacade;
use Nette;
use Nette\Application\UI\Form;
use stdClass;

/**
 * Presenter for displaying and managing individual posts.
 */
final class PostPresenter extends Nette\Application\UI\Presenter
{
    /**
     * Dependency injection of the PostFacade.
     */
    public function __construct(
        private readonly PostFacade    $facade,
        private readonly CommentFacade $commentFacade,
    )
    {
    }


    /**
     * Fetches the post and its related comments, then sends them to the template.
     */
    public function renderShow(int $id): void
    {
        $post = $this->facade->getById($id);
        if (!$post) {
            $this->error('Post not found');
        }

        $this->template->post     = $post;
        $this->template->comments = $post->related('comment')->order('created_at');
    }


    /**
     * Form for adding comments to a post.
     */
    protected function createComponentCommentForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Your name:')
             ->setRequired();

        $form->addEmail('email', 'Email:');

        $form->addTextArea('content', 'Comment:')
             ->setRequired();

        $form->addSubmit('send', 'Publish comment');
        $form->onSuccess[] = $this->commentFormSucceeded(...);

        return $form;
    }


    /**
     * Handles the successful submission of the comment form.
     */
    private function commentFormSucceeded(stdClass $data): void
    {
        $postId = $this->getParameter('id');
        if ($postId === null) {
            $this->error('Post not found');
        }

        $this->commentFacade->insert((int) $postId, $data->content, $data->name, $data->email);

        $this->flashMessage('Thank you for your comment', 'success');
        $this->redirect('this');
    }
}
