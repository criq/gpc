<?php

namespace GPC;

class StringParse
{
	protected $string;

	public function __construct(string $string)
	{
		$this->setString($string);
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
			return new Line($index + 1, $string);
		}, $array, array_keys($array)));
	}
}
