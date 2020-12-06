<?php


namespace App\Domain;


use App\Controller\Dto\CreateCrewmateDto;
use App\Controller\Dto\DepartmentDto;
use App\Controller\Dto\UpdateCrewmateDto;
use App\Entity\Crewmate;
use App\Entity\CrewmateStats;
use App\Entity\ShipCrewmates;
use App\Repository\CrewmateRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ShipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CrewmateService
{

    /**
     * @var ShipRepository
     */
    private $shipRepository;
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;

    /**
     * @var CrewmateRepository
     */
    private $crewmateRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * CrewmateService constructor.
     * @param ShipRepository $shipRepository
     * @param DepartmentRepository $departmentRepository
     * @param CrewmateRepository $crewmateRepository
     * @param EntityManagerInterface $manager
     */
    public function __construct(ShipRepository $shipRepository, DepartmentRepository $departmentRepository, CrewmateRepository $crewmateRepository, EntityManagerInterface $manager)
    {
        $this->shipRepository = $shipRepository;
        $this->departmentRepository = $departmentRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->manager = $manager;
    }

    public function update(int $id, UpdateCrewmateDto $dto): ?JsonResponse
    {
        $crewmate = $this->crewmateRepository->find($id);
        $department = $this->departmentRepository->find($dto->departmentId);
        $ship = $this->shipRepository->find($dto->shipId);

        if ($crewmate === null || $department === null || $ship === null) {
            return new JsonResponse([
                'code' => 'not found'
            ], 404);
        }

        $crewmate->setName($dto->name);
        $crewmate->setLastName($dto->lastName);
        $crewmate->setMainDepartment($department);

        $shipCrewmates = $crewmate->getShipCrewmates()->first();

        $shipCrewmatesNew = new ShipCrewmates();
        $shipCrewmatesNew->setShip($ship);
        $shipCrewmatesNew->setCrewmate($crewmate);

        $stats = $crewmate->getCrewmateStats();
        $stats->setStrength($dto->strength);
        $stats->setPhysicalCondition($dto->physicalCondition);
        $stats->setIntelligence($dto->intelligence);
        $stats->setExperience($dto->experience);
        $stats->setDexterity($dto->dexterity);

        $this->manager->remove($shipCrewmates);
        $this->manager->persist($shipCrewmatesNew);
        $this->manager->persist($stats);
        $this->manager->persist($crewmate);
        $this->manager->flush();

        return null;
    }

    public function create(CreateCrewmateDto $dto): ?JsonResponse
    {
        $crewmate = new Crewmate();
        $department = $this->departmentRepository->find($dto->departmentId);
        $ship = $this->shipRepository->find($dto->shipId);

        $checkLogin = $this->crewmateRepository->findOneBy([
           'login' => $dto->login
        ]);

        if ($checkLogin !== null ) {
            return new JsonResponse([
                'code' => 'login taken'
            ], 409);
        }

        if ($department === null || $ship === null) {
            return new JsonResponse([
                'code' => 'not found'
            ], 404);
        }

        $crewmate->setName($dto->name);
        $crewmate->setLastName($dto->lastName);
        $crewmate->setMainDepartment($department);
        $crewmate->setLogin($dto->login);
        $crewmate->setPassword($dto->password);
        $crewmate->setCreatedAt(new \DateTime());

        $shipCrewmatesNew = new ShipCrewmates();
        $shipCrewmatesNew->setShip($ship);
        $shipCrewmatesNew->setCrewmate($crewmate);

        $stats = new CrewmateStats();
        $stats->setStrength($dto->strength);
        $stats->setPhysicalCondition($dto->physicalCondition);
        $stats->setIntelligence($dto->intelligence);
        $stats->setExperience($dto->experience);
        $stats->setDexterity($dto->dexterity);
        $stats->setCrewmate($crewmate);

        $this->manager->persist($crewmate);
        $this->manager->persist($shipCrewmatesNew);
        $this->manager->persist($stats);
        $this->manager->flush();

        return new JsonResponse([
            'data' => [ 'id' => $crewmate->getId() ]
        ], 201);
    }

}