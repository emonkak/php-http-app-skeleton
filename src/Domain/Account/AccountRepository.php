<?php

namespace App\Domain\Account;

use App\Support\Persistable;
use App\Support\PersistableInterface;
use Emonkak\Database\PDOInterface;
use Emonkak\Orm\Fetcher\ClassFetcher;
use Emonkak\Orm\SelectBuilder;

class AccountRepository implements PersistableInterface
{
    use Persistable;

    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var ClassFetcher
     */
    private $fetcher;

    public function __construct(PDOInterface $pdo)
    {
        $this->pdo = $pdo;
        $this->fetcher = new ClassFetcher(Account::class);
    }

    public function allAccounts(): array
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->getResult($this->pdo, $this->fetcher);
    }

    public function accountOfId(int $accountId): ?Account
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->where('account_id' , '=', $accountId)
            ->getResult($this->pdo, $this->fetcher)
            ->firstOrDefault();
    }

    public function accountOfEmailAddress(string $emailAddress): ?Account
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->where('email_address' , '=', $emailAddress)
            ->getResult($this->pdo, $this->fetcher)
            ->firstOrDefault();
    }

    public function lockedAccountOfEmail(string $emailAddress): ?Account
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->where('email_address' , '=', $emailAddress)
            ->forUpdate()
            ->getResult($this->pdo, $this->fetcher)
            ->firstOrDefault();
    }

    protected function getPdoForEntity(Account $account): PDOInterface
    {
        return $this->pdo;
    }
}
