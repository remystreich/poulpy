<?php
namespace App\Controller;

use App\Entity\Application;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class NewApplicationController extends AbstractController{

    public function __construct(
        private readonly EntityManagerInterface $em
    ){}
    /**
     * @throws Exception
     */
    public function __invoke(Request $request, UserRepository $userRepository, CampaignRepository $campaignRepository): JsonResponse{
        $body= json_decode($request->getContent());
        $campaign= $campaignRepository->find($body->campaignId);
        $user = $userRepository->find($body->userId);

        $application = new Application();
        $application->setStatus($body->status);
        $application->setCampaign($campaign);
        $application->setUser($user);

        $this->em->persist($application);
        $this->em->flush();

        return new JsonResponse(['succes' => $application],Response::HTTP_CREATED);
    }
}

