<?php

namespace GPC;

class StatementCollection extends \ArrayObject
{
	public function getTransactions(): TransactionCollection
	{
		return new TransactionCollection(array_merge(...array_map(function (Statement $statement) {
			return $statement->getTransactions()->getArrayCopy();
		}, $this->getArrayCopy())));
	}

	public function getFirst(): ?Statement
	{
		return array_values($this->getArrayCopy())[0] ?? null;
	}
}
