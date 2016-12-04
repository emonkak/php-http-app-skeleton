<?php

namespace App\Supports;

use Emonkak\Database\PDOTransactionInterface;

trait Transactional
{
    /**
     * @var PDOTransactionInterface
     */
    private $transaction;

    /**
     * @param callable $action
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
