<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\Type\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/view/{id}", name="article_view")
     * @ParamConverter("get", class="App\Entity\Article")
     *
     * @param Article $article
     *
     * @return Response
     */
    public function view(Article $article)
    {
        return $this->render('article/article-view.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     * @ParamConverter("get", class="App\Entity\Article")
     * @Security("article.isAuthor(user)")
     *
     * @param Article $article
     *
     * @return Response
     */
    public function edit(Article $article)
    {
        if ($this->getUser() !== $article->getAuthor()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(ArticleType::class, $article);

        return $this->render('article/article.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/new", name="article_new")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        /** @var User $user */
        $user = $this->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->setAuthor($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Article created successfully!'
            );

            return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
        }

        return $this->render('article/article.html.twig', [
            'form' => $form->createView(),
            'pagetitle' => 'Create new article',
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/author/{id}", name="author_articles")
     * @ParamConverter("get", class="App\Entity\User")
     * @param User $user
     *
     * @return Response
     */
    public function authorArticles(User $user)
    {
        return $this->render('feed.html.twig', [
            'pagetitle' => 'Articles by ' . $user->getEmail(),
            'items' => $user->getArticles()
        ]);
    }
}
