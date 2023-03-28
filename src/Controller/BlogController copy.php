<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\fileExists;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BlogController extends AbstractController
{
	// private $em;

	// public function __construct(EntityManagerInterface $em)
	// {
	//     $this->em = $em;
	// }
	// dd($repo->findAll());

	#[Route('/', name: 'app_home', methods: ['GET'])]
	public function index(PostRepository $repo, Request $request, PaginatorInterface $paginator): Response
	{
		// $posts = $repo->findAll();

		$posts = $paginator->paginate(
			$repo->paginationQuery(),
			// page quon doit passer, param 2 page par default
			$request->query->get('page', 1),
			// Number post/page
			2
		);

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
	}

	#[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
	public function create(Request $request, EntityManagerInterface $em): Response
	{
		// create new objet post
		$post = new Post();
		// create form
		$form = $this->createForm(PostFormType::class, $post);

		// get input data
		$form->handleRequest($request);
		// store data
		if ($form->isSubmitted() && $form->isValid()) {
			$newPost = $form->getData();
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
			// set date
			$newPost->setCreatedAt(new DateTime());
			$em->persist($newPost);
			$em->flush();
			$this->addFlash(
				'notice',
				'Post créé!'
			);

			return $this->redirectToRoute('app_home');
		}

		return $this->render('blog/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	#[Route('/edit/{id}', name: 'app_update', methods: ['GET', 'POST'])]
	public function update(int $id, PostRepository $repo, Request $request, EntityManagerInterface $em): Response
	{
		// recuperer le post avec le bon id
		$post = $repo->find($id);
		// create form
		$form = $this->createForm(PostFormType::class, $post);

		// dans notre formualre on recupere les data input
		$form->handleRequest($request);
		// dd($form->getData());
		$imagePath = $form->get('url_img')->getData();

		if ($form->isSubmitted() && $form->isValid()) {
			$racineSite = $this->getParameter('kernel.project_dir');
			// dd($this->getParameter('kernel.project_dir'));
			// verifie si une nouvelle image a été choisi ou pas
			if ($imagePath) {
				// verifie que l'image existe et qu'il n'est pas vide
				if ($post->getUrlImg() !== null) {
					if (file_exists($racineSite . $post->getUrlImg())) {
						$racineSite . $post->getUrlImg();
					}
					// new img name if same name image
					$newFileName = uniqid() . '.' . $imagePath->guessExtension();

					// deplacer le fichier upload  dans public/uploads
					try {
						$imagePath->move(
							$this->getParameter('kernel.project_dir') . '/public/uploads',
							$newFileName
						);
					} catch (FileException $e) {
						return new Response($e->getMessage());
					}
					// on store le filename en BDD
					$post->setUrlImg('/uploads/' . $newFileName);

					$em->flush();
					return $this->redirectToRoute('app_home');
				}
			} else {
				// $post->setTitle($form->get('title')->getData());
				$post->setTitle($form->get('title')->getData());
				$post->setContent($form->get('content')->getData());
				$post->setCategory($form->get('category')->getData());
				$post->setAuthor($form->get('author')->getData());
				// $post->setUpdatedAt(new DateTime());

				$em->flush();
				return $this->redirectToRoute('app_home');
			}
		}

		return $this->render('blog/edit.html.twig', [
			'form' => $form->createView()
		]);
	}
}
