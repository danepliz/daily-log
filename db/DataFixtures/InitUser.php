<?php
namespace DataFixtures;

use models\User\Group;

use models\User;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class InitUser implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$group = new Group();
		$group->setName('Superadmin');
		$group->setDescription('The Superadmin Group');
		$group->setActive(TRUE);
		
		$user = new User();
		$user->setUsername('superadmin');
		$user->setAddress('Kalikasthan');
		$user->setEmail('rajesh.sharma@f1soft.com');
		$user->setFirstname('Rajesh');
		$user->setLastname('Sharma');
		$user->setMobile('9801079789');
		$user->setPassword(md5('123456'));
		$user->setUsercode('007');
		$user->setGroup($group);

		$manager->persist($group);
		$manager->persist($user);
		$manager->flush();
	}
}