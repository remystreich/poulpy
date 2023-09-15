<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyCampaignsController extends AbstractController {
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->json([
                'error' => 'User not authenticated'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $campaigns = $user->getCampaigns();

        return $this->json($campaigns);
    }
}
