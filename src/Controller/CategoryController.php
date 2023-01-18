<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository)
    {
        $Categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $Categories,
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
      // Create a new Category Object
      $category = new Category();
      // Create the associated Form
      $form = $this->createForm(CategoryType::class, $category);
      // Get data from HTTP request
      $form->handleRequest($request);
      // Was the form submitted ?
      if ($form->isSubmitted()) {
        $categoryRepository->save($category, true);   
          // Deal with the submitted data
          // For example : persiste & flush the entity
          // And redirect to a route that display the result
      }
  
      // Render the form
      return $this->renderForm('category/new.html.twig', [
          'form' => $form,
      ]);
    }

    #[Route('/{categoryName}', name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$categoryName) {
            throw $this->createNotFoundException(
                'No category with the name : '.$categoryName.' found in category table.'
            );
        }

        $shows = $programRepository->findBy(['category' => $category], ['id' => 'DESC'], 3);

        return $this->render('category/show.html.twig', [
            'shows' => $shows,
            'category' => $category
        ]);
    }
}


