<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordController extends AbstractController
{
    public function __construct()
    {

    }

    public function __invoke(User $data): JsonResponse
    {
        return new JsonResponse(['message' => 'Password reset']);
    }
}
