<?php

declare(strict_types=1);

namespace App\UI\Home;

use App\Model\PostFacade;
use Nette;


/**
 * Presenter for the homepage.
 */
final class HomePresenter extends Nette\Application\UI\Presenter
{
    /**
     * Dependency injection of the PostFacade.
     */
    public function __construct(
        private readonly PostFacade $facade,
    )
    {
    }


    /**
     * Fetches and sends the latest public articles to the template.
     */
    public function renderDefault(): void
    {
        $this->template->posts = $this->facade
            ->getPublicArticles()
            ->limit(5);
    }
}
