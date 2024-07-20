<?php

namespace GPC;

class Transaction
{
	protected $lines;

	public function addLine(Line $line): Transaction
	{
		$this->getLines()[] = $line;

		return $this;
	}

	public function getLines(): LineCollection
	{
		if (is_null($this->lines)) {
			$this->lines = new LineCollection;
		}

		return $this->lines;
	}
}
