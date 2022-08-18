<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request,EntityManagerInterface $entityManager,Censurator $censurator): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);
        $wishForm = $this->createForm(WishType::class, $wish);



        $wishForm->handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){
            $wish->setDescription($censurator->purify($wish->getDescription()));
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success','Wish added !');

            return $this->redirectToRoute('detail',['id'=> $wish->getId()]);
        }

        return $this->renderForm('wish/create.html.twig',[
            'wishForm' => $wishForm
        ]);
    }

    #[Route('/wishes', name: 'wishes')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(array('wish' => $this->getUser()->getId()));

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
