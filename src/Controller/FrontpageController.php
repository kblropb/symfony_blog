<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontpageController extends AbstractController
{
    /**
     * @Route("/", name="app_frontpage")
     * @param ArticleRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(ArticleRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $queryBuilder = $repository->getAllQueryBuilder();
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('feed.html.twig', [
            'pagetitle' => 'All records',
            'pagination' => $pagination,
        ]);
    }
}
