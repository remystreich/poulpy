<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserLoginController extends AbstractController{
    public function __construct(
        private readonly EntityManagerInterface $em
    ){}


    public function __invoke(Request $request, JWTTokenManagerInterface $JWTTokenManager, UserRepository $userRepository): JsonResponse
    {
        $client = HttpClient::create();
        $body = json_decode($request->getContent(), true);
        $response = $client->request('GET', 'http://localhost:3005/me?code='.$body['token']);

        if ($response->getStatusCode() !== 200) {
            return new JsonResponse(['error' => 'Invalid response'], 400);
        }

        $discordData = json_decode($response->getContent());

        if (!isset($discordData->userId)) {
            return new JsonResponse(['error' => 'Utilisateur non autorisé'], 401);
        }
            $discordId = $discordData->userId;
            $user = $userRepository->findOneBy(['discordId'=>$discordId]);

            if (!$user) {
                $user = new User();
                $user->setRoles($discordData->roles);
                $user->setDiscordId($discordId);
                $user->setGuildId($discordData->guildId);
                $user->setName($discordData->displayName);
                $user->setCreatedAt(new \DateTimeImmutable());

                $this->em->persist($user);
                $this->em->flush();
            }

            $token = $JWTTokenManager->create($user);
            return new JsonResponse(['token' => $discordData]);
        }

}
