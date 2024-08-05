<?php

namespace GPC;

class StringParse
{
	protected $flavor;
	protected $string;

	public function __construct(Flavor $flavor, string $string)
	{
		$this->setFlavor($flavor);
		$this->setString($string);
	}

	public function setFlavor(Flavor $flavor): StringParse
	{
		$this->flavor = $flavor;

		return $this;
	}

	public function getFlavor(): Flavor
	{
		return $this->flavor;
	}

	public function setString(string $string): StringParse
	{
		$this->string = $string;

		return $this;
	}

	public function getString(): string
	{
		return $this->string;
	}

	public function getLines(): LineCollection
	{
		$array = preg_split("/\n/", $this->getString());

		return new LineCollection(array_map(function (string $string, int $index) {
			return new Line($this->getFlavor(), $index + 1, $string);
		}, $array, array_keys($array)));
	}

	public function getStatements(): StatementCollection
	{
		return $this->getLines()->getStatements();
	}
}
