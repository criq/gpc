<?php

namespace GPC;

class FileParse
{
	protected $flavor;
	protected $file;

	public function __construct(Flavor $flavor, \Katu\Files\File $file)
	{
		$this->setFlavor($flavor);
		$this->setFile($file);
	}

	public function setFlavor(Flavor $flavor): FileParse
	{
		$this->flavor = $flavor;

		return $this;
	}

	public function getFlavor(): Flavor
	{
		return $this->flavor;
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
		return new StringParse($this->getFlavor(), iconv("Windows-1250", "UTF-8", $this->getFile()->get()));
	}
}
