<?php declare(strict_types=1);

require __DIR__.'/../../vendor/autoload.php';

$container = App\Bootstrap::boot()
                          ->createContainer();

$pw = getenv('ADMIN_PASSWORD');
if ($pw === false) {
    throw new Exception('Admin password not set');
}

$manager = $container->getByType(App\Model\UserFacade::class);
$manager->add('admin', 'admin@admin.cz', $pw);

// PHP migrations must return integer, which represents how many queries were run.
return 1;
