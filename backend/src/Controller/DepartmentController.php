<?php


namespace App\Controller;


use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController implements TokenAuthenticatedInterface
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;

    /**
     * DepartmentController constructor.
     * @param DepartmentRepository $departmentRepository
     */
    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @Route(name="departments", path="api/v1/departments")
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->departmentRepository->findAll()
        ]);
    }


}