<?php

namespace App\DataFixtures;

use App\Entity\Crewmate;
use App\Entity\CrewmateStations;
use App\Entity\CrewmateStats;
use App\Entity\Department;
use App\Entity\Ship as ShipEntity;
use App\Entity\ShipCrewmates;
use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StationDepartments extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $departments = [];
        $ships = [];

        for ($i = 0; $i < 10; $i++) {
            $ship = new ShipEntity();
            $ship->setEngines(100);
            $ship->setHull(100);
            $ship->setName($faker->company);

            $ships[] = $ship;

            $manager->persist($ship);
        }

        $manager->flush();


        foreach (['ENG', 'CAP', 'MED', 'NAV', 'SEC'] as $item) {
            $department = new Department();
            $department
                ->setCode($item)
                ->setCreatedAt(new \DateTime());

            $departments[$item] = $department;

            $manager->persist($department);
        }

        $stationCodes = [
            'ENG' => [
                'engineer',
                'calibrator',
                'programmer',
            ],
            'CAP' => [
                'captain',
                'secretary',
            ],
            'MED' => [
                'doctor',
                'nurse',
            ],
            'NAV' => [
                'navigator',
                'pilot',
            ],
            'SEC' => [
                'spaceMarine',
                'marine',
                'guard',
            ],
        ];

        $stations = [];

        foreach ($stationCodes as $key => $item){
            foreach ($item as $code) {
                $station = new Station();
                $station
                    ->setCreatedAt(new \DateTime())
                    ->setDepartment($departments[$key])
                    ->setCode($code);

                $stations[] = $station;

                $manager->persist($station);
            }
        }

        for ($i = 0; $i < 200; $i++) {
            $crewmate = new Crewmate();
            $stats = (new CrewmateStats())
                ->setCrewmate($crewmate)
                ->setExperience($faker->numberBetween(0, 10))
                ->setIntelligence($faker->numberBetween(0, 10))
                ->setPhysicalCondition($faker->numberBetween(0, 10))
                ->setStrength($faker->numberBetween(0, 10))
                ->setDexterity($faker->numberBetween(0, 10));

            /** @var Station $station */
            $station = $faker->randomElement($stations);
            $crewmateStation = new CrewmateStations();
            $crewmateStation
                ->setCrewmate($crewmate)
                ->setDateFrom(new \DateTime())
                ->setStation($station);

            $crewmate
                ->setName($faker->name)
                ->setLastName($faker->lastName)
                ->setRole('admin')
                ->setLogin($crewmate->getName() . '.' . $crewmate->getLastName())
                ->setPassword('test')
                ->setCreatedAt(new \DateTime())
                ->setCrewmateStats($stats)
                ->setMainDepartment($station->getDepartment())
                ->addCrewmateStation($crewmateStation);

            $shipCrewmates = new ShipCrewmates();
            $shipCrewmates->setCrewmate($crewmate)->setShip($faker->randomElement($ships));

            $manager->persist($crewmate);
            $manager->persist($stats);
            $manager->persist($crewmateStation);
            $manager->persist($shipCrewmates);
        }

        $manager->flush();
    }
}
