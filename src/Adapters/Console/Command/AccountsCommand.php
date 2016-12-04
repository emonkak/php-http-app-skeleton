<?php

namespace App\Adapters\Console\Command;

use App\Domain\Account\AccountRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AccountsCommand extends Command
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        parent::__construct();

        $this->accountRepository = $accountRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('accounts')
            ->setDescription('Dumps the registered accounts.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accounts = $this->accountRepository->allAccounts()
            ->select(function($account) {
                return array_except($account->toArray(), ['password']);
            })
            ->toArray();

        $output->writeLn(json_encode($accounts, JSON_PRETTY_PRINT));
    }
}
