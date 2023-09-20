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

class ApplyController extends AbstractController{

    public function __construct(
        private readonly EntityManagerInterface $em
    ){}
    /**
     * @throws Exception
     */
    public function __invoke(Request $request, int $campaign_id, UserRepository $userRepository, CampaignRepository $campaignRepository): JsonResponse{
        $body= json_decode($request->getContent());
        $campaign= $campaignRepository->find($campaign_id);
        $user= $this->getUser();
        $application = new Application();
        $application->setStatus($body->status);
        $application->setCampaign($campaign);
        $application->setUser($user);

        $this->em->persist($application);
        $this->em->flush();

        return new JsonResponse(['succes' => $application],Response::HTTP_CREATED);
    }
}

