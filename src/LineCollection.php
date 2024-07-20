<?php

namespace GPC;

class LineCollection extends \ArrayObject
{
	public function filterHeaderLines(): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) {
			return $line->getIsHeaderLine();
		})));
	}

	public function filterExchangeLines(): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) {
			return $line->getIsExchangeLine();
		})));
	}

	public function filterDetailLines(): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) {
			return $line->getIsDetailLine();
		})));
	}

	public function filterMessage1Lines(): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) {
			return $line->getIsMessage1();
		})));
	}

	public function filterMessage2Lines(): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) {
			return $line->getIsMessage2();
		})));
	}

	public function sortByLine(): LineCollection
	{
		$array = $this->getArrayCopy();
		usort($array, function (Line $a, Line $b) {
			return $a->getLine() > $b->getLine() ? 1 : -1;
		});

		return new static($array);
	}

	public function getStatements(): StatementCollection
	{
		$statements = new StatementCollection;

		foreach ($this->sortByLine()->getArrayCopy() as $line) {
			if ($line->getIsHeaderLine()) {
				$statement = new Statement;
				$statements[] = $statement;
			}

			$statement->addLine($line);
		}

		return $statements;
	}

	public function getFirst(): ?Line
	{
		return array_values($this->getArrayCopy())[0] ?? null;
	}
}
