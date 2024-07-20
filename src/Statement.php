<?php

namespace GPC;

class Statement
{
	protected $lines;

	public function addLine(Line $line): Statement
	{
		$this->getLines()[] = $line;

		return $this;
	}

	public function getLines(): LineCollection
	{
		if (is_null($this->lines)) {
			$this->lines = new LineCollection;
		}

		return $this->lines;
	}

	public function getHeader(): Header
	{
		return new Header($this->getLines()->filterHeaderLines()->getFirst());
	}

	public function getTransactions(): TransactionCollection
	{
		$transactions = new TransactionCollection;

		foreach ($this->getLines()->sortByLine() as $line) {
			if ($line->getIsExchangeLine()) {
				$transaction = new Transaction($this);
				$transactions[] = $transaction;
			}
			if (!$line->getIsHeaderLine()) {
				$transaction->addLine($line);
			}
		}

		return $transactions;
	}
}
