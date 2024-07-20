<?php

namespace GPC;

class Transaction
{
	protected $statement;
	protected $lines;

	public function __construct(Statement $statement)
	{
		$this->setStatement($statement);
	}

	public function setStatement(Statement $statement): Transaction
	{
		$this->statement = $statement;

		return $this;
	}

	public function getStatement(): Statement
	{
		return $this->statement;
	}

	public function getLines(): LineCollection
	{
		if (is_null($this->lines)) {
			$this->lines = new LineCollection;
		}

		return $this->lines;
	}

	public function addLine(Line $line): Transaction
	{
		$this->getLines()[] = $line;

		return $this;
	}

	public function getHeader(): Header
	{
		return $this->getStatement()->getHeader();
	}

	public function getExchange(): Exchange
	{
		return new Exchange($this->getLines()->filterExchangeLines()->getFirst());
	}

	public function getDetail(): Detail
	{
		return new Detail($this->getLines()->filterDetailLines()->getFirst());
	}

	public function getMessage1(): Message1
	{
		return new Message1($this->getLines()->filterMessage1Lines()->getFirst());
	}

	public function getMessage2(): Message2
	{
		return new Message2($this->getLines()->filterMessage2Lines()->getFirst());
	}
}
