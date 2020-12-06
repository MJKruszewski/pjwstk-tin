<?php


namespace App\Domain;


use App\Controller\Dto\DepartmentDto;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DepartmentService
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * DepartmentService constructor.
     * @param DepartmentRepository $departmentRepository
     * @param EntityManagerInterface $manager
     */
    public function __construct(DepartmentRepository $departmentRepository, EntityManagerInterface $manager)
    {
        $this->departmentRepository = $departmentRepository;
        $this->manager = $manager;
    }

    public function update(int $id, DepartmentDto $dto): ?JsonResponse
    {
        $department = $this->departmentRepository->find($id);

        if ($department === null) {
            return new JsonResponse([
                'code' => 'bad department'
            ], 400);
        }

        $department->setCode($dto->code);

        $this->manager->persist($department);
        $this->manager->flush();

        return null;
    }

    public function create( DepartmentDto $dto): ?JsonResponse
    {
        $department = new Department();
        $department->setCode($dto->code)->setCreatedAt(new \DateTime());

        $this->manager->persist($department);
        $this->manager->flush();

        return null;
    }

}