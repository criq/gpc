<?php

namespace GPC;

class Account
{
	protected $accountId;
	protected $bankId;

	public function __construct(string $accountId, string $bankId)
	{
		$this->setAccountId($accountId);
		$this->setBankId($bankId);
	}

	public function setAccountId(string $accountId): Account
	{
		$this->accountId = $accountId;

		return $this;
	}

	public function setBankId(string $bankId): Account
	{
		$this->bankId = $bankId;

		return $this;
	}
}
