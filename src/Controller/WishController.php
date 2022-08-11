<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        return $this->renderForm('wish/create.html.twig',[
            'wishForm' => $wishForm
        ]);
    }

    #[Route('/wishes', name: 'wishes')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();

        return $this->render('wish/list.html.twig',[
            "wishes" => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail($id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        return $this->render('wish/detail.html.twig',[
            "wish" => $wish
        ]);
    }
}
