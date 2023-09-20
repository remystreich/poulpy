<?php
namespace App\Controller;

use App\Repository\ApplicationRepository;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateApplicationController extends AbstractController{

    public function __construct(
        private readonly EntityManagerInterface $em
    ){}
    /**
     * @throws Exception
     */
    public function __invoke(Request $request, int $id, UserRepository $userRepository, CampaignRepository $campaignRepository, ApplicationRepository $applicationRepository): JsonResponse{
        $body = json_decode($request->getContent());


        $application = $applicationRepository->find($id);
        $owner = $this->getUser();



        $status= $request->request->get('status');
        if ($status){
            $application->setStatus($status);
        }

        $this->em->flush();
        return new JsonResponse(['succes'=>$application], Response::HTTP_OK);

    }
}
