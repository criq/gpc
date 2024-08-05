<?php

namespace GPC;

class Amount
{
	protected $amount;
	protected $currency;

	public function __construct(float $amount, Currency $currency)
	{
		$this->setAmount($amount);
		$this->setCurrency($currency);
	}

	public function setAmount(float $amount): Amount
	{
		$this->amount = $amount;

		return $this;
	}

	public function getAmount(): float
	{
		return $this->amount;
	}

	public function setCurrency(Currency $currency): Amount
	{
		$this->currency = $currency;

		return $this;
	}

	public function getCurrency(): Currency
	{
		return $this->currency;
	}

	public function getFormatted(): string
	{
		return implode(" ", [
			$this->getAmount(),
			$this->getCurrency()->getCode(),
		]);
	}
}
