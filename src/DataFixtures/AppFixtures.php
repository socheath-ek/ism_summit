<?php
namespace App\DataFixtures;

use App\Entity\Registration;
use App\Entity\Summit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@ism.de');
        $admin->setName('ISM Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Create summits
        $summits = [
            ['Hamburg', 'ISM Campus Hamburg', 'Grotestr. 2, 22767 Hamburg', '2025-09-15', 150, true],
            ['Dortmund', 'ISM Campus Dortmund', 'Otto-Hahn-Str. 19, 44227 Dortmund', '2025-10-10', 100, false],
            ['Munich', 'ISM Campus Munich', 'Müllerstraße 55, 80469 München', '2025-11-20', 120, false],
        ];

        $summitEntities = [];
        foreach ($summits as [$city, $loc, $addr, $date, $cap, $active]) {
            $s = new Summit();
            $s->setCity($city);
            $s->setLocationName($loc);
            $s->setAddress($addr);
            $s->setEventDate(new \DateTime($date));
            $s->setCapacity($cap);
            $s->setIsActive($active);
            $manager->persist($s);
            $summitEntities[] = $s;
        }

        // Create test registrations
        $meals = ['standard', 'vegan', 'vegetarian', 'halal', 'gluten-free'];
        $companies = ['SAP SE', 'Siemens AG', 'Deutsche Bank', 'BMW Group', 'Allianz'];
        $firstNames = ['Anna', 'Thomas', 'Maria', 'Klaus', 'Sophie', 'Michael', 'Laura', 'Andreas'];
        $lastNames = ['Müller', 'Schmidt', 'Weber', 'Fischer', 'Wagner', 'Becker', 'Hoffmann'];

        for ($i = 0; $i < 20; $i++) {
            $reg = new Registration();
            $reg->setFirstName($firstNames[array_rand($firstNames)]);
            $reg->setLastName($lastNames[array_rand($lastNames)]);
            $reg->setEmail('guest' . $i . '@example.com');
            $reg->setCompany($companies[array_rand($companies)]);
            $reg->setJobTitle(['Manager', 'Director', 'Analyst', 'Engineer'][rand(0,3)]);
            $reg->setPhone('+49 ' . rand(100,999) . ' ' . rand(1000000,9999999));
            $reg->setMealPreference($meals[array_rand($meals)]);
            $reg->setNeedsParking((bool)rand(0,1));
            $reg->setNeedsAccommodation((bool)rand(0,1));
            $reg->setNewsletterConsent((bool)rand(0,1));
            $reg->setDataProtectionConsent(true);
            $reg->setStatus(['confirmed', 'confirmed', 'confirmed', 'cancelled'][rand(0,3)]);
            $reg->setSummit($summitEntities[0]);
            $manager->persist($reg);
        }

        $manager->flush();
    }
}
