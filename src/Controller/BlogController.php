<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostFormType;
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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        // create new objet post
        $post = new Post();
        // create form
        $form = $this->createForm(PostFormType::class, $post);
        // dans notre formualre on recupere les data input
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPost = $form->getData();
            // dd($newPost);

            // image
            $imagePath = $form->get('url_img')->getData();
            // dd($imagePath);
            // verifie si une image a été choisi ou pas
            if ($imagePath) {
                // new img name if same name image
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                // try de deplacer le fichier upload temporaire dans public/uploads
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                // on set url_img => uploads/newnamefile
                $newPost->setUrlImg('/uploads/' . $newFileName);
            }

            // on set la date
            $newPost->setCreatedAt(new DateTime());

            $em->persist($newPost);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('blog/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
