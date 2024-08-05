<?php

namespace GPC;

use Katu\Tools\Calendar\Time;
use Pankki\Account;
use Pankki\AccountNumber;
use Pankki\BankCode;
use Pankki\CurrencyCollection;
use Pankki\VariableSymbol;
use Pankki\Worth;

class Transaction
{
	protected $statement;
	protected $lines;

	public function __construct(Statement $statement)
	{
		$this->setStatement($statement);
	}

	public function getLine(): int
	{
		return $this->getLines()->getFirst()->getLine();
	}

	public function getFlavor(): Flavor
	{
		return $this->getStatement()->getFlavor();
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

	public function getDate(): ?Time
	{
		if ($this->getExchange()->getDate()) {
			$time = Time::createFromDateTime(Time::createFromFormat("dmy", $this->getExchange()->getDate()))->setTime(0, 0, 0, 0);
		}

		return ($time ?? null);
	}

	public function getDebtorAccount(): Account
	{
		return new Account(
			new AccountNumber($this->getFlavor()->getDecodedAccountNumber($this->getExchange()->getDebtorAccountNumber())),
			new BankCode($this->getExchange()->getBankCode()),
		);
	}

	public function getDebtorName(): string
	{
		return trim($this->getExchange()->getDebtorName());
	}

	public function getCreditorAccountNumber(): AccountNumber
	{
		return new AccountNumber($this->getFlavor()->getDecodedAccountNumber($this->getExchange()->getCreditorAccountNumber()));
	}

	public function getWorth(): Worth
	{
		return new Worth(
			$this->getExchange()->getAmount() * .01 * $this->getExchange()->getAccountingMultiplier(),
			CurrencyCollection::createDefault()->getById($this->getFlavor()->getDecodedCurrencyId($this->getExchange()->getCurrencyId()))
		);
	}

	public function getVariableSymbol(): VariableSymbol
	{
		return new VariableSymbol($this->getExchange()->getVariableSymbol());
	}

	public function getConstantSymbol(): string
	{
		return $this->getExchange()->getConstantSymbol();
	}

	public function getSpecificSymbol(): string
	{
		return $this->getExchange()->getSpecificSymbol();
	}
}
