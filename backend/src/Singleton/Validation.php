<?php


namespace App\Singleton;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Validation
{
    public static function handle(ConstraintViolationListInterface $errors): ?JsonResponse
    {
        if (count($errors) > 0) {
            $err = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $err[] = [
                    'code' => $error->getCode(),
                    'message' => $error->getMessage(),
                    'cause' => $error->getCause(),
                    'parameters' => $error->getParameters(),
                ];
            }

            return new JsonResponse(['errors' => $err], 400);
        }

        return null;
    }
}