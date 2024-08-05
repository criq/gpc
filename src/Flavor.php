<?php

namespace GPC;

abstract class Flavor
{
	abstract public function getTitle(): string;

	public function getDecodedAccountId(string $accountId): string
	{
		return $accountId;
	}

	public function getDecodedCurrencyId(string $currencyId): string
	{
		return $currencyId;
	}
}
