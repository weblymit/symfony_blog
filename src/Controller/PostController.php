<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    // #[Route('/post', name: 'app_post')]
    // public function index(): Response
    // {
    //     return $this->render('post/index.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }


    // #[Route('/post/create', name: 'create_post')]
    // public function createPost(EntityManagerInterface $entityManager): Response
    // {
    //     $post = new Post();
    //     $post->setTitle('Toto en Russie');
    //     $post->setCategory('Politique');
    //     $post->setAuthor('Toto');
    //     $post->setContent("Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit est perferendis voluptatem fuga optio distinctio tempora quibusdam praesentium aspernatur ea excepturi, accusamus reiciendis similique molestiae id nisi illo maxime voluptatibus?");
    //     $post->setUrlImg('https://media.ldlc.com/r1600/ld/products/00/05/82/93/LD0005829395_1.jpg');
    //     $post->setCreatedAt(new DateTime());

    //     // tell Doctrine you want to (eventually) save the Product (no queries yet)
    //     $entityManager->persist($post);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     return new Response('Saved new product with id ' . $post->getId());
    // }

    // #[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
    // public function create(Request $request, EntityManagerInterface $em)
    // {
    //     if ($request->isMethod('POST')) {
    //         // $request->query() //recuperer data de type GET
    //         $data = $request->request->all();
    //         $post = new Post;
    //         $post->setTitle('Toto en Russie');
    //         $post->setCategory('Politique');
    //         $post->setAuthor('Toto');
    //         $post->setContent("Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit est perferendis voluptatem fuga optio distinctio tempora quibusdam praesentium aspernatur ea excepturi, accusamus reiciendis similique molestiae id nisi illo maxime voluptatibus?");
    //         $post->setUrlImg('https://media.ldlc.com/r1600/ld/products/00/05/82/93/LD0005829395_1.jpg');
    //         $post->setCreatedAt(new DateTime());

    //         // prepare requete
    //         $em->persist($post);
    //         $em->flush();

    //         // redirect
    //         // return $this->redirect('/');
    //         return $this->redirectToRoute('app_home');
    //     }
    //     return $this->render('blog/create.html.twig');
    // }
}
