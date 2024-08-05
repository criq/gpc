<?php

namespace GPC;

class TransactionCollection extends \ArrayObject
{
	public function getFirst(): ?Transaction
	{
		return array_values($this->getArrayCopy())[0] ?? null;
	}
}
