<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontpageController extends AbstractController
{
    /**
     * @Route("/", name="app_frontpage")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        return $this->render('feed.html.twig', [
            'pagetitle' => 'All records',
            'items' => $repository->findAll()
        ]);
    }
}
