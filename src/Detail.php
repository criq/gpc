<?php

namespace GPC;

class Detail extends LineContainer
{
	// Identifikace transakce (bankovní identifikátor)
	public function getTransactionReference(): string
	{
		return mb_substr($this->getString(), 3, 26);
	}

	// Datum odepsání z účtu protistrany ve formátu DDMMRR (pro příchozí tuzemské
	// platby z jiné banky)
	public function getDebtorDate(): string
	{
		return mb_substr($this->getString(), 29, 6);
	}

	// Název protistrany nebo komentář
	public function getDescription(): string
	{
		return mb_substr($this->getString(), 35, 92);
	}
}
