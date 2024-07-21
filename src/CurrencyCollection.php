<?php

namespace GPC;

use Katu\Tools\Calendar\Timeout;
use Katu\Types\TIdentifier;
use Katu\Types\TURL;

class CurrencyCollection extends \ArrayObject
{
	public static function createDefault(): CurrencyCollection
	{
		$timeout = new Timeout("1 week");

		return \Katu\Cache\General::get(new TIdentifier(__CLASS__, __FUNCTION__), $timeout, function () use ($timeout) {
			$res = \Katu\Cache\URL::get(new TURL("https://www.iban.cz/currency-codes"), $timeout);
			$dom = \Katu\Tools\DOM\DOM::crawlHTML($res);

			return new CurrencyCollection(array_values(array_filter($dom->filter(".table.table-bordered.downloads.tablesorter tbody tr")->each(function (\Symfony\Component\DomCrawler\Crawler $e) {
				$code = $e->filter("td:nth-child(3)")->text();
				$number = $e->filter("td:nth-child(4)")->text();
				if ($code && $number) {
					return new Currency($code, $number);
				}
			}))));
		});
	}
}
