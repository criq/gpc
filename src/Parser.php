<?php

namespace GPC;

class Parser
{
	public function createStringParse(Flavor $flavor, string $string): StringParse
	{
		return new StringParse($flavor, $string);
	}

	public function createFileParse(Flavor $flavor, \Katu\Files\File $file): FileParse
	{
		return new FileParse($flavor, $file);
	}
}
