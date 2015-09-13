<?php
namespace DataFixtures;

class CommonData{
	
	private $names = array(
			"Emerita Hoggan","Iva Caudillo","Nita Gilligan","Berta Mansell","Maragret Brush"
			,"Toshiko Hageman","Suzi Bennefield","Kathlyn Guerrera","Linette Shavers","Louetta Crowner"
			,"Krystina Heiss","Allena Delano","Cher Krell","Ofelia Verona","Derrick Koppes","Lucile Lager"
			,"Efren Vadnais","Jerome Vermillion","Earnestine Heras","Raguel Houghtaling","Andre Lovelace"
			,"Yukiko Ibrahim","Tara Arrant","Tamica Mcraney","Adriene Heger","Tierra Snuggs","Paulina Lall"
			,"Janetta Dandrea","Romaine Stott","Jayme Giroux","Shellie Garrard","Earl Pines","Jackeline Guntrum"
			,"Danyel Crosland","Anna Blackledge","Rich Geno","Dusti Viviano","Grayce Paredes","Pinkie Neil"
			,"Millie Bilderback","Margrett Flore","James Raybon","Kittie Hinshaw","Lucien Godsey"
			,"Irvin Wagaman","Julianna Drago","Rhonda Heintzelman","Sanjuana Kissee","Dinorah Yerger","Gaye Mundy"
		);
	
	private $companyNames = array(
			"Acme, inc.","Widget Corp","123 Warehousing","Demo Company","Smith and Co.","Foo Bars","ABC Telecom"
			,"Fake Brothers","QWERTY Logistics","Demo, inc.","Sample Company","Sample, inc","Acme Corp","Allied Biscuit"
			,"Ankh-Sto Associates","Extensive Enterprise","Galaxy Corp","Globo-Chem","Mr. Sparkle","Globex Corporation"
			,"LexCorp","LuthorCorp","North Central Positronics","Omni Consimer Products","Praxis Corporation"
			,"Sombra Corporation","Sto Plains Holdings","Tessier-Ashpool","Wayne Enterprises","Wentworth Industries"
			,"ZiffCorp","Bluth Company","Strickland Propane","Thatherton Fuels","Three Waters","Water and Power"
			,"Western Gas & Electric","Mammoth Pictures","Mooby Corp","Gringotts","Thrift Bank","Flowers By Irene"
			,"The Legitimate Businessmens Club","Osato Chemicals","Transworld Consortium","Universal Export"
			,"United Fried Chicken","Virtucon","Kumatsu Motors","Keedsler Motors","Powell Motors","Industrial Automation"
			,"Sirius Cybernetics Corporation","U.S. Robotics and Mechanical Men","Colonial Movers","Corellian Engineering Corporation"
			,"Incom Corporation","General Products","Leeding Engines Ltd.","Blammo","Input, Inc.","Mainway Toys","Videlectrix"
			,"Zevo Toys","Ajax","Axis Chemical Co.","Barrytron","Carrys Candles","Cogswell Cogs","Spacely Sprockets"
			,"General Forge and Foundry","Duff Brewing Company","Dunder Mifflin","General Services Corporation"
			,"Monarch Playing Card Co.","Krustyco","Initech","Roboto Industries","Primatech","Sonky Rubber Goods"
			,"St. Anky Beer","Stay Puft Corporation","Vandelay Industries","Wernham Hogg","Gadgetron","Burleigh and Stronginthearm"
			,"BLAND Corporation","Nordyne Defense Dynamics","Petrox Oil Company","Roxxon","McMahon and Tate","Sixty Second Avenue"
			,"Charles Townsend Agency","Spade and Archer","Megadodo Publications","Rouster and Sideways","C.H. Lavatory and Sons"
			,"Globo Gym American Corp","The New Firm","SpringShield","Compuglobalhypermeganet","Data Systems","Gizmonic Institute"
			,"Initrode","Taggart Transcontinental","Atlantic Northern","Niagular","Plow King","Big Kahuna Burger","Big T Burgers and Fries"
			,"Chez Quis","Chotchkies","The Frying Dutchman","Klimpys","The Krusty Krab","Monks Diner","Milliways","Minuteman Cafe"
			,"Taco Grande","Tip Top Cafe","Moes Tavern","Central Perk","Chasers"
		);
	
	public static function getRandomCompanyName(){
		return array_rand($this->companyNames);
	}
	
	public static function getRandomName(){
		return array_rand($this->names);
	}
	
	public static function getRandomFirstName(){
		$name = array_rand($this->names);
		$parts = explode($name);
		return $parts[0];
	}
	
	public static function getRandomLastName(){
		$name = array_rand($this->names);
		$parts = explode($name);
		return $parts[1];
	}
}