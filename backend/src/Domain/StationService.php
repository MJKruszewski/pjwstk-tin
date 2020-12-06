<?php


namespace App\Domain;


use App\Controller\Dto\AddCrewmateStation;
use App\Controller\Dto\StationDto;
use App\Entity\CrewmateStations;
use App\Entity\Station;
use App\Repository\CrewmateRepository;
use App\Repository\CrewmateStationsRepository;
use App\Repository\DepartmentRepository;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class StationService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var StationRepository
     */
    private $stationRepository;
    /**
     * @var CrewmateRepository
     */
    private $crewmateRepository;
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;
    /**
     * @var CrewmateStationsRepository
     */
    private $crewmateStations;

    /**
     * StationService constructor.
     * @param EntityManagerInterface $manager
     * @param StationRepository $stationRepository
     * @param CrewmateRepository $crewmateRepository
     * @param DepartmentRepository $departmentRepository
     * @param CrewmateStationsRepository $crewmateStations
     */
    public function __construct(EntityManagerInterface $manager, StationRepository $stationRepository, CrewmateRepository $crewmateRepository, DepartmentRepository $departmentRepository, CrewmateStationsRepository $crewmateStations)
    {
        $this->manager = $manager;
        $this->stationRepository = $stationRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->departmentRepository = $departmentRepository;
        $this->crewmateStations = $crewmateStations;
    }

    public function create(StationDto $dto): ?JsonResponse
    {
        $department = $this->departmentRepository->find($dto->departmentId);

        if ($department === null) {
            return new JsonResponse([
                'code' => 'bad department'
            ], 400);
        }

        $station = new Station();
        $station->setDepartment($department)->setCode($dto->code)->setCreatedAt(new \DateTime());

        $this->manager->persist($station);
        $this->manager->flush();

        return null;
    }

    public function update(int $id, StationDto $dto): ?JsonResponse
    {
        $department = $this->departmentRepository->find($dto->departmentId);

        if ($department === null) {
            return new JsonResponse([
                'code' => 'bad department'
            ], 400);
        }

        $station = $this->stationRepository->find($id);

        if ($station === null) {
            return new JsonResponse([
                'code' => 'bad station'
            ], 400);
        }

        $station->setDepartment($department)->setCode($dto->code);

        $this->manager->persist($station);
        $this->manager->flush();

        return null;
    }

    public function assignToStation(AddCrewmateStation $addStation): ?JsonResponse
    {
        $station = $this->stationRepository->find($addStation->stationId);
        $crewmate = $this->crewmateRepository->find($addStation->crewmateId);

        if ($station === null || $crewmate === null) {
            return new JsonResponse(['code' => 'not-found'], 404);
        }

        $entity = new CrewmateStations();
        $entity->setCrewmate($crewmate);
        $entity->setStation($station);
        $entity->setDateFrom(new \DateTime($addStation->dateFrom));

        $this->manager->persist($entity);
        $this->manager->flush();

        return null;
    }


}