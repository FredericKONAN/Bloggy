<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\CommentsRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Meilisearch\Bundle\Searchable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: '`posts`')]
//#[ORM\UniqueConstraint(name: 'unique_slug_for_published_date', columns: ['published_at', 'slug'])]
#[ORM\HasLifecycleCallbacks]
class Post
{
    use Timestampable;

    public const  NBRE_ELEMENTS_PAR_PAGE = 10;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields:['titre'], updatable: false)]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $author = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comments::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tags;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getAuthor(): ?Utilisateur
    {
        return $this->author;
    }

    public function setAuthor(?Utilisateur $author): static
    {
        $this->author = $author;

        return $this;
    }


//    public function getPathParams():array
//    {
//        return [
//            'date'=> $this->getPublishedAt()->format('Y-m-d'),
//            'slug'=> $this->getSlug()
//        ];
//    }

/**
 * @return Collection<int, Comments>
 */
public function getComments(): Collection
{
    return $this->comments;
}

public function addComment(Comments $comment): static
{
    if (!$this->comments->contains($comment)) {
        $this->comments->add($comment);
        $comment->setPost($this);
    }

    return $this;
}

public function removeComment(Comments $comment): static
{
    if ($this->comments->removeElement($comment)) {
        // set the owning side to null (unless already changed)
        if ($comment->getPost() === $this) {
            $comment->setPost(null);
        }
    }

    return $this;
}

    public  function getActiveComments():Collection
    {
        return $this->getComments()->matching(CommentsRepository::createIsActiveCriteria());
    }

    public function __toString(): string
    {

//        return '#'. $this->getId() .'-'. $this->getTitre();
        return sprintf('#%d %s', $this->getId(), $this->getTitre() );
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function isPublished(): bool
    {
        return !is_null($this->getPublishedAt()) && $this->getPublishedAt() <= new \DateTimeImmutable();
    }


}
