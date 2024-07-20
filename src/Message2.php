<?php

namespace GPC;

class Message2 extends LineContainer
{
	// Pro tuzemské platby:
	// • třetí část zprávy pro příjemce (dalších 35 znaků)
	// Pro zahraniční nebo SEPA platby:
	// • první část zprávy pro příjemce (prvních 35 znaků)
	// Pro ostatní transakce:
	// • doplnění informací o transakci, poslední část
	public function getMessagePart1(): string
	{
		return mb_substr($this->getString(), 3, 35);
	}

	// Pro tuzemské platby:
	// • čtvrtá část zprávy pro příjemce (posledních 35 znaků)
	// Pro zahraniční a SEPA platby:
	// • pro korporátní klienty druhá část zprávy pro příjemce (dalších 35 znaků), JINAK
	// název protistrany
	public function getMessagePart2(): string
	{
		return mb_substr($this->getString(), 38, 35);
	}

	// Pro tuzemské platby a ostatní typy transakcí:
	// • nepoužívá se, doplněno mezerami z důvodu sjednocení délky
	// Pro zahraniční a SEPA platby:
	// • pouze pro korporátní klienty třetí část zprávy pro příjemce (dalších 35 znaků)
	public function getMessagePart3(): string
	{
		return mb_substr($this->getString(), 73, 35);
	}
}
