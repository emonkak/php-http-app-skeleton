<?php

namespace App\Support\Database;

use Emonkak\Database\PDOTransactionInterface;

trait Transactional
{
    /**
     * @var PDOTransactionInterface
     */
    private $transaction;

    /**
     * @return mixed
     */
    public function transaction(callable $action)
    {
        $this->transaction->beginTransaction();

        try {
            $result = $action();

            $this->transaction->commit();
        } catch (\Exception $e) {
            $this->transaction->rollback();

            throw $e;
        }

        return $result;
    }
}
