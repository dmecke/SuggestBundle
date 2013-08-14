<?php

namespace Cunningsoft\SuggestBundle\Entity;

use Cunningsoft\SuggestBundle\Entity\AuthorInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Suggestion
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var AuthorInterface
     *
     * @ORM\ManyToOne(targetEntity="AuthorInterface")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var Comment[]
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="suggestion")
     */
    private $comments;

    /**
     * @var Vote[]
     *
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="suggestion")
     */
    private $votes;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param AuthorInterface $author
     */
    public function setAuthor(AuthorInterface $author)
    {
        $this->author = $author;
    }

    /**
     * @return AuthorInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return int
     */
    public function getVotesOverall()
    {
        $votesOverall = 0;
        foreach ($this->votes as $vote) {
            $votesOverall += $vote->getVotes();
        }

        return $votesOverall;
    }

    /**
     * @param AuthorInterface $author
     *
     * @return int
     */
    public function getVotesByAuthor(AuthorInterface $author)
    {
        foreach ($this->votes as $vote) {
            if ($vote->getAuthor()->getId() == $author->getId()) {
                return $vote->getVotes();
            }
        }

        return 0;
    }
}
