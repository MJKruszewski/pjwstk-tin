<?php


namespace App\Controller;


use App\Controller\Dto\LoginDto;
use App\Entity\AuthorizationToken;
use App\Repository\AuthorizationTokenRepository;
use App\Repository\CrewmateRepository;
use App\Singleton\Encrypt;
use App\Singleton\LoggedCrewmate;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TokenController extends AbstractController
{
    /**
     * @var CrewmateRepository
     */
    private $crewmateRepository;
    /**
     * @var AuthorizationTokenRepository
     */
    private $tokenRepository;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * TokenController constructor.
     * @param CrewmateRepository $crewmateRepository
     * @param AuthorizationTokenRepository $tokenRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(CrewmateRepository $crewmateRepository, AuthorizationTokenRepository $tokenRepository, ValidatorInterface $validator)
    {
        $this->crewmateRepository = $crewmateRepository;
        $this->tokenRepository = $tokenRepository;
        $this->validator = $validator;
    }

    /**
     * @Route(name="login", path="api/v1/auth/login", methods={"POST","OPTIONS","HEAD"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(Request $request): JsonResponse
    {
        $message = json_decode($request->getContent() ?? '', true);

        $login = new LoginDto();
        $login->login = $message['login'];
        $login->password = $message['password'];

        $errors = $this->validator->validate($login);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString, 400);
        }

        $password = Encrypt::hash($login->password);

        try {
            $crewmate = $this->crewmateRepository->findOneBy([
                'login' => $login->login,
                'password' => $password,
            ]);
        } catch (NoResultException $e) {
            return new JsonResponse(['code' => 'not-found'], 404);
        }

        if($crewmate === null) {
            return new JsonResponse(['code' => 'not-found'], 404);
        }

        $token = new AuthorizationToken();
        $token
            ->setCrewmate($crewmate)
            ->setCreatedAt(new \DateTime())
            ->setExpireAt((new \DateTime())->modify('+5 hour'))
            ->setToken(Uuid::uuid4());

        $this->getDoctrine()->getManager()->persist($token);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(
            [
                'data' => $token
            ]
        );
    }
}