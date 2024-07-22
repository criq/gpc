<?php

namespace GPC;

class Parser
{
	public function createFileParse(Flavor $flavor, \Katu\Files\File $file): FileParse
	{
		return new FileParse($flavor, $file);
	}
}
