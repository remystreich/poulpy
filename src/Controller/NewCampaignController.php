<?php
namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewCampaignController extends AbstractController {
    /**
     * @throws \Exception
     */
    public function __construct(
        private readonly EntityManagerInterface $em
    ){}
    public function __invoke(Request $request): JsonResponse
    {

        $uploadedFile = $request->files->get('imageFile');
        $startDateString = $request->request->get('startDate');
        $startDate = new DateTime($startDateString);
        $user = $this->getUser();

        if (!$user){
          return new JsonResponse(['error'=>"L'utilisateur doit etre authentifié"],Response::HTTP_UNAUTHORIZED);
        };

        $campaign = new Campaign();
        $campaign->setName($request->request->get('name'));
        $campaign->setDescription($request->request->get('description'));
        $campaign->setStartDate($startDate);
        $campaign->setPlayersNumber($request->request->get('playersNumber'));
        $campaign->setStatus($request->request->get('status'));
        $campaign->setCreatedAt(new \DateTimeImmutable());
        $campaign->setAuthor($this->getUser());

        $campaign->imageFile = $uploadedFile;

        $this->em->persist($campaign);
        $this->em->flush();

        return new JsonResponse(['succes'=>$campaign], Response::HTTP_CREATED);
    }
}