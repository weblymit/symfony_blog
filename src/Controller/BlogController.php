<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog', methods: ['GET'])]
    public function index(PostRepository $repo): Response
    {
        // dd($repo->findAll());
        $posts = $repo->findAll();
        return $this->render('blog/index.html.twig', compact('posts'));
    }

    #[Route('/post/{id}', name: 'app_show', methods: ['GET'])]
    public function show(int $id, PostRepository $repo): Response
    {
        $post = $repo->find($id);
        // dd($post);
        return $this->render('blog/show.html.twig', compact('post'));
    }

    #[Route('/post/delete/{id}', name: 'app_delete', methods: ['GET', 'DELETE'])]
    public function delete(int $id, PostRepository $repo, EntityManagerInterface $em): Response
    {
        $post = $repo->find($id);
        $em->remove($post);
        $em->flush();


        return $this->redirectToRoute('app_blog');
        // return $this->render('blog/show.html.twig', compact('post'));
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('blog/about.html.twig');
    }
}
