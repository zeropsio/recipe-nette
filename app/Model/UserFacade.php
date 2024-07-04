<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\DuplicateNameException;
use Nette\Database\Explorer;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class UserFacade implements Authenticator
{
    // Minimum password length requirement for users
    public const PasswordMinLength = 10;

    // Database table and column names
    private const
        TableName = 'users',
        ColumnId = 'id',
        ColumnName = 'username',
        ColumnPasswordHash = 'password',
        ColumnEmail = 'email',
        ColumnRole = 'role';

    // Dependency injection of database explorer and password utilities
    public function __construct(
        private readonly Explorer  $database,
        private readonly Passwords $passwords,
    )
    {
    }


    /**
     * Authenticate a user based on provided credentials.
     * Throws an AuthenticationException if authentication fails.
     * @throws AuthenticationException
     */
    public function authenticate(string $user, string $password): SimpleIdentity
    {
        // Fetch the user details from the database by username
        $row = $this->database->table(self::TableName)
                              ->where(self::ColumnName, $user)
                              ->fetch();

        // Authentication checks
        if (!$row) {
            throw new AuthenticationException('The username is incorrect.', self::IdentityNotFound);

        } elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
            throw new AuthenticationException('The password is incorrect.', self::InvalidCredential);

        } elseif ($this->passwords->needsRehash($row[self::ColumnPasswordHash])) {
            $row->update([
                self::ColumnPasswordHash => $this->passwords->hash($password),
            ]);
        }

        // Return user identity without the password hash
        $arr = $row->toArray();
        unset($arr[self::ColumnPasswordHash]);
        return new SimpleIdentity($row[self::ColumnId], $row[self::ColumnRole], $arr);
    }


    /**
     * Add a new user to the database.
     * Throws a DuplicateNameException if the username is already taken.
     * @throws AssertionException
     * @throws DuplicateNameException
     */
    public function add(string $username, string $email, string $password): void
    {
        // Validate the email format
        Validators::assert($email, 'email');

        // Attempt to insert the new user into the database
        try {
            $this->database->table(self::TableName)->insert([
                self::ColumnName         => $username,
                self::ColumnPasswordHash => $this->passwords->hash($password),
                self::ColumnEmail        => $email,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }
}
