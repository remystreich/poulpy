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

    public function __invoke(Request $request,JWTTokenManagerInterface $JWTTokenManager, UserRepository $userRepository): JsonResponse
    {

        $client = HttpClient::create();


        $body= json_decode($request->getContent());
        $discordData = $client->request('GET', 'http://localhost:3005/me?code='.$body->token);



        if (!$discordData->discordId){
            return 'Utilisateur non autorisé';
        }

        else {
            $discordId = $discordData->discordId;
            $user = $userRepository->findOneBy(['discordId'=>$discordId]);

            if (!$user) {
                $user = new User();
                $user->setRoles([]);
                $user->setDiscordId($discordId);
                $user->setName($discordData->displayName);
                $user->setGuildId($discordData->guildId);
                $user->setCreatedAt(new \DateTimeImmutable());

                $this->em->persist($user);
                $this->em->flush();
            }

            $token = $JWTTokenManager->create($user);
            return new JsonResponse(['token' => $token]);
        }
    }
}
