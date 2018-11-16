<?php

namespace Ueg\Crm\Model;

class Coinoptions
{
	public function getMintOptions()
	{
		$mint = array(
			"CC",
            "D",
            "O",
            "P",
            "S",
            "W"
		);

		return $mint;
	}

	public function getDesigOptions()
	{
		$desig = array(
			"PR,PF",
            "MS",
            "BU",
            "AU",
            "XF,EF",
            "VF",
            "F",
            "VG",
            "G",
            "AG",
            "FA,FR",
            "PO"
		);

		return $desig;
	}

	public function getGradeOptions()
	{
		$grade = array(
			"70",
			"69",
			"68",
			"67",
			"66",
			"65",
			"64",
			"63",
			"62",
			"61",
			"60",
			"58",
			"55",
			"53",
			"50",
			"45",
			"40",
			"35",
			"30",
			"25",
			"20",
			"15",
			"12",
			"10",
			"8"
		);

		return $grade;
	}

	public function getServiceOptions()
	{
		$service = array(
			"PCGS",
	        "NGC",
	        "PCGS/NGC",
	        "ANACS",
	        "ICG",
	        "Other",
	        "Uncertified"
		);

		return $service;
	}

	public function getStatusOptions()
	{
		$status = array(
			"Need",
	        "Upgrade",
	        "Current",
	        "Filled"
		);

		return $status;
	}

	public function getDenomOptions()
	{
		$denom = array(
			"$5000",
	        "$2500",
	        "$1000",
	        "$500",
	        "$250",
	        "$200",
	        "$100",
	        "$50, $25, $10, $5",
	        "$100, $50, $25, $10",
	        "$50",
	        "$30",
	        "$25",
	        "$20",
	        "$10",
	        "$5",
	        "$3",
	        "$2.50",
	        "$1",
	        "$0.50",
	        "$0.25",
	        "$0.10",
	        "$0.05",
	        "$0.01",
	        "100 Pound",
	        "Pound",
	        "100 FR",
	        "50 FR",
	        "20 FR",
	        "500 Y",
	        "100 Y",
	        "20 Y",
	        "10 Y",
	        "100 Soles",
	        "500 Lirot",
	        "1500 Peso",
	        "100 Peso",
	        "50 Peso",
	        "20 Peso",
	        "10 Peso",
	        "5 Peso",
	        "Ducat",
	        "1oz",
	        "Lirot",
	        "Corona",
	        "20 KR",
	        "Sov",
	        "Real",
	        "Other",
		);

		return $denom;
	}

	public function getTypeOptions()
	{
		$type = array(
			"American Gold Eagles",
            "American Gold Buffalo Coins",
            "Ultra High Relief/High Relief",
            "$20 Gold Saint Gaudens",
            "$20 Gold Liberty",
            "$10 Gold Liberty",
            "$10 Gold Indian",
            "$5 Gold Liberty",
            "$5 Gold Indian",
            "$3 Gold Indian",
            "$2.50 Gold Liberty",
            "$2.50 Gold Indian",
            "$1 Gold Type 3",
            "$1 Gold Type 2",
            "$1 Gold Type 1",
            "Other US Gold",
            "World Gold",
            "Silver American Eagles",
            "Morgan Silver Dollar",
            "Peace Silver Dollar",
            "Walking Liberty Silver Half Dollar",
            "Other US Certified Silver Rarities",
            "Other Silver",
            "American Platinum Eagles",
            "Other Platinum",
            "Palladium",
            "Copper",
            "Nickel",
            "Other Collectables"
		);

		return $type;
	}

	public function getMetalOptions()
	{
		$metal = array(
			"Gold",
            "Silver",
            "Platinum",
            "Palladium",
            "Other"
		);

		return $metal;
	}
}