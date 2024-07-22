<?php

namespace GPC;

abstract class LineContainer
{
	protected $line;

	public function __construct(?Line $line)
	{
		$this->setLine($line);
	}

	public function setLine(?Line $line): LineContainer
	{
		$this->line = $line;

		return $this;
	}

	public function getLine(): ?Line
	{
		return $this->line;
	}

	public function getString(): ?string
	{
		return $this->getLine() ? $this->getLine()->getString() : null;
	}

	public function getFlavor(): Flavor
	{
		return $this->getLine()->getFlavor();
	}
}
