<?php


namespace App\Controller;


use App\Controller\Dto\DepartmentDto;
use App\Controller\Dto\PageRequest;
use App\Controller\Dto\StationDto;
use App\Domain\DepartmentService;
use App\Entity\Department;
use App\Entity\Station;
use App\Repository\DepartmentRepository;
use App\Singleton\LoggedCrewmate;
use App\Singleton\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DepartmentController extends AbstractController implements TokenAuthenticatedInterface
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var DepartmentService
     */
    private $departmentService;

    /**
     * DepartmentController constructor.
     * @param DepartmentRepository $departmentRepository
     * @param ValidatorInterface $validator
     * @param DepartmentService $departmentService
     */
    public function __construct(DepartmentRepository $departmentRepository, ValidatorInterface $validator, DepartmentService $departmentService)
    {
        $this->departmentRepository = $departmentRepository;
        $this->validator = $validator;
        $this->departmentService = $departmentService;
    }


    /**
     * @Route(name="updateDepartment", path="api/v1/departments/{id}", methods={"PATCH","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function update(int $id, Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new DepartmentDto();
        $dto->code = $message['code'] ?? null;

        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $result = Validation::handle($this->validator->validate($dto));

        if ($result) {
            return $result;
        }

        return $this->departmentService->update($id, $dto) ?: new Response(null, 204);
    }

    /**
     * @Route(name="deleteDepartment", path="api/v1/departments/{id}", methods={"DELETE","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function delete(int $id): Response {
        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $department = $this->departmentRepository->find($id);

        if ($department === null) {
            return new JsonResponse([
                'code' => 'bad station'
            ], 400);
        }

        $this->getDoctrine()->getManager()->remove($department);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }

    /**
     * @Route(name="createDepartment", path="api/v1/departments", methods={"POST","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function create(Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new DepartmentDto();
        $dto->code = $message['code'] ?? null;

        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $result = Validation::handle($this->validator->validate($dto));

        if ($result ) {
            return $result;
        }

        return $this->departmentService->create($dto) ?: new Response(null, 204);
    }

    /**
     * @Route(name="departments", path="api/v1/departments", methods={"GET","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $itemsPerPage = $request->query->get('itemsPerPage', null);
        $page = $request->query->get('page', null);

        return new JsonResponse([
            'data' => $this->departmentRepository->findBy([], null, $itemsPerPage, $page * $itemsPerPage),
            'meta' => [
                'totalPages' => floor($this->departmentRepository->count([]) / ($itemsPerPage ?: 1))
            ]
        ]);
    }


}