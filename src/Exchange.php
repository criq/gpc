<?php

namespace GPC;

class Exchange extends LineContainer
{
	// Číslo účtu včetně předčíslí – doplněno vodicími nulami na 16 pozic

	// https://www.alis.cz/uploads/dokumentace/keo4/ucetnictvi/banky.html
	// Vnitřní formát čísla účtu je vytvářen permutací dle následujícího principu:
	// Px-předčíslí, pozice x.
	// Cx-Číslo učtu, pozice x.
	// Číslo účtu: P1P2P3P4P5P6C1C2C3C4C5C6C7C8C9C0
	// Vnitřní formát: C0C8C9C6C1C2C3C4C5C7P1P2P3P4P5P6
	public function getCreditorAccountId(): string
	{
		return mb_substr($this->getString(), 3, 16);
	}

	// Číslo účtu protistrany – doplněno vodicími nulami na 16 pozic.
	// V případě zahraničních (cizoměnových) transakcí je číslo účtu protistrany uvedeno
	// ve větě „zpráva pro příjemce“, v tomto poli jsou uvedeny nuly
	public function getDebtorAccountId(): string
	{
		return mb_substr($this->getString(), 19, 16);
	}

	// Identifikátor transakce na 13 pozic, přičemž:
	// 	- pozice 36 (první číslice) určuje typ transakce – 0 pro příkazy vzešlé z příkazu
	// 	k inkasu a 1 pro ostatní platby
	// 	- pozice 43–48 (posledních 6 cifer) určuje sekvenční číslo v historii účtu
	// 	V případě zahraničních (cizoměnových) transakcí jde o identifikátor transakce
	public function getReceiptId(): string
	{
		return mb_substr($this->getString(), 35, 13);
	}

	// Částka zaúčtované transakce – 14 číslic, z toho 12 jsou pozice vyhrazené pro celé
	// číslo (včetně vodicích nul) a zbývající 2 pozice jsou desetinná místa (bez oddělovače)
	// Poznámka: Pro měnu CZK jde o částku v haléřích.
	public function getAmount(): string
	{
		return mb_substr($this->getString(), 48, 12);
	}

	// Kód účtování – 1 pro debetní (odchozí) položku, 2 pro kreditní (příchozí) položku,
	// 4 pro storno debetní položky a 5 pro storno kreditní položky
	public function getAccountingKind(): string
	{
		return mb_substr($this->getString(), 60, 1);
	}

	// Variabilní symbol – doplněn vodicími nulami na 10 pozic
	// V případě zahraničních (cizoměnových) transakcí vyplněno nulami
	public function getVariableSymbol(): string
	{
		return mb_substr($this->getString(), 61, 10);
	}

	// Kód banky protistrany (např. 0300)
	// V případě zahraničních (cizoměnových) transakcí vyplněno nulami, SWIFT kód banky
	// je uveden ve větě „zpráva pro příjemce“
	public function getBankId(): string
	{
		return mb_substr($this->getString(), 73, 4);
	}

	public function getConstantSymbol(): string
	{
		return mb_substr($this->getString(), 77, 4);
	}

	public function getSpecificSymbol(): string
	{
		return mb_substr($this->getString(), 81, 10);
	}

	public function getConversionDate(): string
	{
		return mb_substr($this->getString(), 91, 6);
	}

	public function getDebtorName(): string
	{
		return mb_substr($this->getString(), 97, 20);
	}

	// https://www.iban.cz/currency-codes
	public function getCurrencyId(): string
	{
		return mb_substr($this->getString(), 117, 5);
	}

	// Datum zaúčtování ve formátu DDMMRR
	public function getDate(): string
	{
		return mb_substr($this->getString(), 122, 6);
	}
}
