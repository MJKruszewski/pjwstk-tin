<?php


namespace App\Singleton;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Validation
{
    public static function handle(ConstraintViolationListInterface $errors): ?JsonResponse
    {
        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            return new JsonResponse($errorsString, 400);
        }

        return null;
    }
}