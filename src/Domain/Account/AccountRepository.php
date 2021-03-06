<?php

namespace App\Domain\Account;

use App\Support\Database\Persistable;
use Emonkak\Database\PDOInterface;
use Emonkak\Orm\Fetcher\ObjectFetcher;
use Emonkak\Orm\SelectBuilder;

class AccountRepository
{
    use Persistable;

    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var ObjectFetcher
     */
    private $fetcher;

    public function __construct(PDOInterface $pdo)
    {
        $this->pdo = $pdo;
        $this->fetcher = new ObjectFetcher(Account::class);
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
