<?php

namespace App\Domain\Account;

use App\Supports\Persistable;
use Emonkak\Database\PDOInterface;
use Emonkak\Orm\Fetcher\ClassFetcher;
use Emonkak\Orm\Fetcher\FetcherInterface;
use Emonkak\Orm\SelectBuilder;

class AccountRepository
{
    use Persistable;

    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var FetcherInterface
     */
    private $fetcher;

    /**
     * @param PDOInterface $pdo
     */
    public function __construct(PDOInterface $pdo)
    {
        $this->pdo = $pdo;
        $this->fetcher = new ClassFetcher(Account::class);
    }

    /**
     * @return Account[]
     */
    public function allAccounts()
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->getResult($this->pdo, $this->fetcher);
    }

    /**
     * @param integer $accountId
     * @return Account|null
     */
    public function accountOfId($accountId)
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->where('account_id' , '=', $accountId)
            ->getResult($this->pdo, $this->fetcher)
            ->firstOrDefault();
    }

    /**
     * @param string $emailAddress
     * @return Account|null
     */
    public function accountOfEmailAddress($emailAddress)
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->where('email_address' , '=', $emailAddress)
            ->getResult($this->pdo, $this->fetcher)
            ->firstOrDefault();
    }

    /**
     * @param string $emailAddress
     * @return Account|null
     */
    public function lockedAccountOfEmail($emailAddress)
    {
        return (new SelectBuilder())
            ->from('accounts')
            ->where('email_address' , '=', $emailAddress)
            ->forUpdate()
            ->getResult($this->pdo, $this->fetcher)
            ->firstOrDefault();
    }

    /**
     * @param Account $account
     */
    public function store(Account $account)
    {
        $this->persist('accounts', $account, $this->pdo);
    }
}
