<?php

namespace GPC;

class Parser
{
	public function createFileParse(\Katu\Files\File $file): FileParse
	{
		return new FileParse($file);
	}
}
