<?php
namespace App\Controller;

use App\Entity\Campaign;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PutCampaignController extends AbstractController {

    public function __construct(
        private readonly EntityManagerInterface $em
    ){}
    /**
     * @throws Exception
     */
    public function __invoke(Request $request, int $id, EntityManagerInterface $entityManager): JsonResponse
    {

        $campaignRepository= $entityManager->getRepository(Campaign::class);
        $campaign = $campaignRepository->find($id);

        if (!$campaign){
            return new JsonResponse(['message'=>'Campagne non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();

        if (!$user || $campaign->getAuthor()->getDiscordId()!==$user->getUserIdentifier()){
            return new JsonResponse(['error'=>"L'utilisateur doit etre authentifié"],Response::HTTP_UNAUTHORIZED);
        }

        $name= $request->request->get('name');
        if($name){
            $campaign->setName($name);
        }

        $description= $request->request->get('description');
        if($description){
            $campaign->setDescription($description);
        }

        $startDateString = $request->request->get('startDate');
        if($startDateString){
            $startDate = new DateTime($startDateString);
            $campaign->setStartDate($startDate);
        }

        $playersNumber =$request->get('playersNumber');
        if($playersNumber){
            $campaign->setPlayersNumber($playersNumber);
        }

        $status =$request->get('status');
        if($status){
            $campaign->setStatus($status);
        }

        $uploadedFile = $request->files->get('imageFile');
        if($uploadedFile){
            $campaign->imageFile = $uploadedFile;
        }

        $campaign->setUpdatedAt(new \DateTimeImmutable());


        $this->em->flush();

        return new JsonResponse(['succes'=>$campaign], Response::HTTP_OK);
    }
}
