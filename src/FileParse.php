<?php

namespace GPC;

class FileParse
{
	protected $file;

	public function __construct(\Katu\Files\File $file)
	{
		$this->setFile($file);
	}

	public function setFile(\Katu\Files\File $file): FileParse
	{
		$this->file = $file;

		return $this;
	}

	public function getFile(): \Katu\Files\File
	{
		return $this->file;
	}

	public function getStringParse(): StringParse
	{
		return new StringParse(iconv("Windows-1250", "UTF-8", $this->getFile()->get()));
	}
}
