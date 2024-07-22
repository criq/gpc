<?php

namespace GPC;

class Quantity
{
	protected $amount;
	protected $currency;

	public function __construct(float $amount, Currency $currency)
	{
		$this->amount = $amount;
		$this->currency = $currency;
	}
}
