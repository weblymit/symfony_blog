<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    // private $em;

    // public function __construct(EntityManagerInterface $em)
    // {
    //     $this->em = $em;
    // }
    #[Route('/', name: 'app_home', methods: ['GET'])]
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


        return $this->redirectToRoute('app_home');
        // return $this->render('blog/show.html.twig', compact('post'));
    }

    #[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post;
        $form = $this->createFormBuilder($post)
            // ->add('title', TextType::class, [
            //     'attr' => ['class' => 'bg-blue-500'],
            // ])
            ->add('title', null, [
                'attr' => ['class' => 'bg-blue-500'],
            ])
            ->add('category', null)
            ->add('author', TextType::class)
            // ->add('urlImg', TextType::class)
            ->add('content', TextareaType::class)
            // ->add('createdAt', DateType::class)
            // ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        // handleRequest => recupere les data du formulaire
        $form->handleRequest($request);

        // verifie si form a été soumis et que les data sont formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // recupere data et on stoque
            // $data = $form->getData();
            // // dd($data);
            // // crée new objet de l'entity
            // $post = new Post;

            // $post->setTitle($data['title']);
            // $post->setCategory($data['category']);
            // $post->setAuthor($data['author']);
            // $post->setContent($data['content']);
            $post->setUrlImg('https://media.gqmagazine.fr/photos/6418337591d17f9554133fe2/16:9/w_2560%2Cc_limit/IphoneApple.png');
            $post->setCreatedAt(new DateTime());

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('blog/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('blog/about.html.twig');
    }
}
