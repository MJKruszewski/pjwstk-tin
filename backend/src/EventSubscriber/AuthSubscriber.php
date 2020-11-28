<?php


namespace App\EventSubscriber;


use App\Controller\TokenAuthenticatedInterface;
use App\Repository\AuthorizationTokenRepository;
use App\Singleton\LoggedCrewmate;
use Doctrine\ORM\NoResultException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationTokenRepository
     */
    private $tokenRepository;

    /**
     * AuthSubscriber constructor.
     * @param AuthorizationTokenRepository $tokenRepository
     */
    public function __construct(AuthorizationTokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if(strtoupper($event->getRequest()->getMethod()) === 'OPTIONS') {
            return;
        }

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if (!($controller instanceof TokenAuthenticatedInterface)) {
            return;
        }


        $tokenValue = $event->getRequest()->headers->get('Authorization');

        if (empty($tokenValue)) {
            throw new AccessDeniedHttpException('This action needs a valid token!');
        }

        try {
            $token = $this->tokenRepository->findValidByToken($tokenValue);
        } catch (NoResultException $i) {
            throw new AccessDeniedHttpException('This action needs a valid token!');
        }

        LoggedCrewmate::setCrewmate($token->getCrewmate());

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}