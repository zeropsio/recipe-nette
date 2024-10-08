<?php declare(strict_types=1);

use App\Exception\DuplicateNameException;

require __DIR__.'/../vendor/autoload.php';


$container = App\Bootstrap::boot()
                          ->createContainer();

if (!isset($argv[3])) {
    echo '
Add new user to database.

Usage: create-user.php <name> <email> <password>
';
    exit(1);
}

[, $name, $email, $password] = $argv;

$manager = $container->getByType(App\Model\UserFacade::class);

try {
    $manager->add($name, $email, $password);
    echo "User $name was added.\n";

} catch (DuplicateNameException $e) {
    echo "Error: duplicate name.\n";
    exit(1);
}
