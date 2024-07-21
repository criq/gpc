<?php

namespace GPC;

class Currency
{
	protected $id;
	protected $code;

	public function __construct(string $code, int $id)
	{
		$this->setId($id);
		$this->setCode($code);
	}

	public function setId(int $id): Currency
	{
		$this->id = $id;

		return $this;
	}

	public function setCode(string $code): Currency
	{
		$this->code = $code;

		return $this;
	}
}
