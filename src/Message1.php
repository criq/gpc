<?php

namespace GPC;

class Message1 extends LineContainer
{
	// Pro tuzemské platby:
	// • první část zprávy pro příjemce (prvních 35 znaků)
	// Pro zahraniční nebo SEPA platby následující informace oddělené mezerou:
	// • částka platby (desetinná čárka a dvě desetinná místa)
	// • měna transakce (např. EUR)
	// • kurz použitý při konverzi měn (šest desetinných míst)
	// Pro ostatní transakce:
	// • další informace o transakci, první část
	public function getMessagePart1(): string
	{
		return mb_substr($this->getString(), 3, 35);
	}

	// Pro tuzemské platby:
	// • druhá část zprávy pro příjemce (dalších 35 znaků)
	// Pro zahraniční nebo SEPA platby:
	// • číslo účtu protistrany (standardně ve formátu IBAN)
	// Pro ostatní transakce:
	// • doplnění informací o transakci, druhá část
	public function getMessagePart2(): string
	{
		return mb_substr($this->getString(), 38, 35);
	}

	// Pro tuzemské platby:
	// • může obsahovat informaci o původní částce transakce, měně a kurzu použitém
	// při konverzi (je-li relevantní)
	// • doplněno mezerami na plnou délku řádku
	// Pro zahraniční nebo SEPA platby:
	// • SWIFT kód banky protistrany (8 nebo 11 znaků, např. CEKOCZPP), doplněno
	// mezerami z důvodu sjednocení délky (jednotné plnění od 21. 10. 2019)
	// Pro ostatní transakce:
	// • doplněno mezerami na plnou délku řádku
	public function getMessagePart3(): string
	{
		return mb_substr($this->getString(), 73, 54);
	}
}
