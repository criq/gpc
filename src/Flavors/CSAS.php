<?php

namespace GPC\Flavors;

class CSAS extends General
{
	public function getTitle(): string
	{
		return "Česká spořitelna";
	}

	// https://www.alis.cz/uploads/dokumentace/keo4/ucetnictvi/banky.html
	// https://www.csas.cz/static_internet/cs/Obchodni_informace-Produkty/Prime_bankovnictvi/Spolecne/Prilohy/ABO_format.pdf
	// Vnitřní formát čísla účtu je vytvářen permutací dle následujícího principu:
	// Px-předčíslí, pozice x.
	// Cx-Číslo učtu, pozice x.

	// Vnitřní formát: C0C8C9C6C1C2C3C4C5C7P1P2P3P4P5P6
	// C0 C8 C9 C6 C1 C2 C3 C4 C5 C7 P1 P2 P3 P4 P5 P6
	//  0  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15

	// Číslo účtu: P1P2P3P4P5P6C1C2C3C4C5C6C7C8C9C0
	// P1 P2 P3 P4 P5 P6 C1 C2 C3 C4 C5 C6 C7 C8 C9 C0
	// 10 11 12 13 14 15  4  5  6  7  8  3  9  1  2  0
	public function getDecodedAccountNumber(string $accountId): string
	{
		return implode([
			$accountId[10],
			$accountId[11],
			$accountId[12],
			$accountId[13],
			$accountId[14],
			$accountId[15],
			$accountId[4],
			$accountId[5],
			$accountId[6],
			$accountId[7],
			$accountId[8],
			$accountId[3],
			$accountId[9],
			$accountId[1],
			$accountId[2],
			$accountId[0],
		]);
	}

	public function getDecodedCurrencyId(string $currencyId): string
	{
		return "00203";
	}
}
