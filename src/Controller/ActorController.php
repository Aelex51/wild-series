<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();

        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ActorRepository $actorRepository): Response
    {
        // Create a new Category Object
        $actor = new Actor();

        $form = $this->createForm(ActorType::class, $actor);
        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actorRepository->save($actor, true);

            return $this->redirectToRoute('program_index');
        }
            return $this->renderForm('actor/new.html.twig', [
                'form' => $form,
                'actor' => $actor
        ]);
    }
}
