<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\ApplyController;
use App\Controller\UpdateApplicationController;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/apply/{campaign_id}',
            controller: ApplyController::class,
            name: 'newApplication',
        ),
        new Put(
            uriTemplate: '/updateApplication/{id}',
            controller: UpdateApplicationController::class,
            name: 'updateApplication'
        ),
        new Delete(),
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['read:applications']]

)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:applications'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:applications'])]
    #[Assert\Regex(
        pattern: '/^[012]+$/',
        message: 'Caractère invalide',
        match: false,
    )]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[Groups(['read:applications'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campaign $Campaign = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[Groups(['read:applications'])]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->Campaign;
    }

    public function setCampaign(?Campaign $Campaign): static
    {
        $this->Campaign = $Campaign;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
