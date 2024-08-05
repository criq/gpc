<?php

namespace GPC;

class Header extends LineContainer
{
	// Číslo účtu včetně předčíslí – doplněno vodicími nulami na 16 pozic
	public function getAccountNumber(): string
	{
		return mb_substr($this->getString(), 3, 16);
	}

	// Majitel účtu (prvních 20 znaků z názvu majitele účtu)
	public function getAccountName(): string
	{
		return mb_substr($this->getString(), 19, 20);
	}

	// Datum počátečního zůstatku ve formátu DDMMRR
	public function getStartBalanceDate(): string
	{
		return mb_substr($this->getString(), 39, 6);
	}

	// Počáteční zůstatek – 14 číslic, z toho 12 jsou pozice vyhrazené pro celé číslo (včetně
	// vodicích nul) a zbývající 2 pozice jsou desetinná místa (bez oddělovače). V případě
	// měny CZK jde tedy o hodnotu v haléřích
	public function getStartBalance(): string
	{
		return mb_substr($this->getString(), 45, 14);
	}

	public function getStartBalanceSymbol(): string
	{
		return mb_substr($this->getString(), 59, 1);
	}

	// Koncový zůstatek – 14 číslic, z toho 12 jsou pozice vyhrazené pro celé číslo (včetně
	// vodicích nul) a zbývající 2 pozice jsou desetinná místa (bez oddělovače). V případě
	// měny CZK jde tedy o hodnotu v haléřích
	public function getEndBalance(): string
	{
		return mb_substr($this->getString(), 60, 14);
	}

	public function getEndBalanceSymbol(): string
	{
		return mb_substr($this->getString(), 74, 1);
	}

	// Suma debetních (odchozích) položek – 14 číslic, z toho 12 jsou pozice vyhrazené
	// pro celé číslo (včetně vodicích nul) a zbývající 2 pozice jsou desetinná místa (bez
	// oddělovače)
	public function getDebitAmount(): string
	{
		return mb_substr($this->getString(), 75, 14);
	}

	public function getDebitSymbol(): string
	{
		return mb_substr($this->getString(), 89, 1);
	}

	// Suma kreditních (příchozích) položek – 14 číslic, z toho 12 jsou pozice vyhrazené
	// pro celé číslo (včetně vodicích nul) a zbývající 2 pozice jsou desetinná místa (bez
	// oddělovače)
	public function getCreditAmount(): string
	{
		return mb_substr($this->getString(), 90, 14);
	}

	public function getCreditSymbol(): string
	{
		return mb_substr($this->getString(), 104, 1);
	}

	// Pořadové číslo výpisu v číslování od začátku roku
	public function getStatementId(): string
	{
		return mb_substr($this->getString(), 105, 3);
	}

	// Datum výpisu ve formátu DDMMRR (pro denní výpisy jde o datum, za které byl výpis
	// generován, jinak jde o poslední datum daného období)
	public function getStatementDate(): string
	{
		return mb_substr($this->getString(), 108, 6);
	}
}
