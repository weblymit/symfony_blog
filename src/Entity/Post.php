<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	#[Assert\NotBlank(["message" => "Champs est obligatoire"])]
	#[Assert\Length([
		'min' => 2,
		'max' => 50,
		'minMessage' => 'Taille de titre minimum {{ limit }} characters ',
		'maxMessage' => 'Taille de titre minimum {{ limit }} characters',
	])]
	private ?string $title = null;

	#[ORM\Column(type: Types::TEXT)]
	#[Assert\NotBlank(["message" => "Champs est obligatoire"])]
	private ?string $content = null;

	#[ORM\Column(length: 255, nullable: true)]
	// #[Assert\NotBlank(["message" => "Champs est obligatoire"])]
	#[Assert\File([
		'maxSize' => '1M',
		'extensions' => [
			'pdf',
		],
		'extensionsMessage' => 'Please upload a valid PDF',
		'maxSizeMessage' => "Le fichier est trop volumineux"
	])]
	private ?string $url_img = null;

	#[ORM\Column(length: 255)]
	#[Assert\NotBlank(["message" => "Champs est obligatoire"])]
	private ?string $category = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
	private ?\DateTimeInterface $created_at = null;

	#[ORM\Column(nullable: true)]
	private ?\DateTimeImmutable $updated_at = null;

	#[ORM\Column(length: 255)]
	#[Assert\NotBlank(["message" => "Champs est obligatoire"])]
	private ?string $author = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(?string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(?string $content): self
	{
		$this->content = $content;

		return $this;
	}

	public function getUrlImg(): ?string
	{
		return $this->url_img;
	}

	public function setUrlImg(?string $url_img): self
	{
		$this->url_img = $url_img;

		return $this;
	}

	public function getCategory(): ?string
	{
		return $this->category;
	}

	public function setCategory(?string $category): self
	{
		$this->category = $category;

		return $this;
	}

	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->created_at;
	}

	public function setCreatedAt(?\DateTimeInterface $created_at): self
	{
		$this->created_at = $created_at;

		return $this;
	}

	public function getUpdatedAt(): ?\DateTimeImmutable
	{
		return $this->updated_at;
	}

	public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
	{
		$this->updated_at = $updated_at;

		return $this;
	}

	public function getAuthor(): ?string
	{
		return $this->author;
	}

	public function setAuthor(?string $author): self
	{
		$this->author = $author;

		return $this;
	}
}
