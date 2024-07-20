<?php

namespace GPC;

class Header
{
	protected $line;

	public function __construct(Line $line)
	{
		$this->setLine($line);
	}

	public function setLine(Line $line): Header
	{
		$this->line = $line;

		return $this;
	}
}
