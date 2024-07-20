<?php

namespace GPC;

class LineCollection extends \ArrayObject
{
	public function filterByPrefix(string $prefix): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) use ($prefix) {
			return $line->getPrefix() == $prefix;
		})));
	}

	public function filterHeaderLines(): LineCollection
	{
		return new static(array_values(array_filter($this->getArrayCopy(), function (Line $line) {
			return $line->getIsHeaderLine();
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
