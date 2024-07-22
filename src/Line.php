<?php

namespace GPC;

class Line
{
	protected $flavor;
	protected $line;
	protected $string;

	public function __construct(Flavor $flavor, int $line, string $string)
	{
		$this->setFlavor($flavor);
		$this->setLine($line);
		$this->setString($string);
	}

	public function setFlavor(Flavor $flavor): Line
	{
		$this->flavor = $flavor;

		return $this;
	}

	public function getFlavor(): Flavor
	{
		return $this->flavor;
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

	public function getIsExchangeLine(): bool
	{
		return $this->getPrefix() == "075";
	}

	public function getIsDetailLine(): bool
	{
		return $this->getPrefix() == "076";
	}

	public function getIsMessage1(): bool
	{
		return $this->getPrefix() == "078";
	}

	public function getIsMessage2(): bool
	{
		return $this->getPrefix() == "079";
	}
}
