<?php

namespace GPC;

class Line
{
	protected $string;
	protected $line;

	public function __construct(int $line, string $string)
	{
		$this->setLine($line);
		$this->setString($string);
	}

	public function setLine(int $line): Line
	{
		$this->line = $line;

		return $this;
	}

	public function getLine(): int
	{
		return $this->line;
	}

	public function setString(string $string): Line
	{
		$this->string = $string;

		return $this;
	}

	public function getString(): string
	{
		return $this->string;
	}

	public function getPrefix(): string
	{
		return substr($this->getString(), 0, 3);
	}

	public function getIsHeaderLine(): bool
	{
		return $this->getPrefix() == "074";
	}
}
