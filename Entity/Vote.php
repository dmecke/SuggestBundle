<?php

namespace Cunningsoft\SuggestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Vote
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
     * @var Suggestion
     *
     * @ORM\ManyToOne(targetEntity="Suggestion", inversedBy="votes")
     */
    private $suggestion;

    /**
     * @var AuthorInterface
     *
     * @ORM\ManyToOne(targetEntity="AuthorInterface")
     */
    private $author;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $votes;

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
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param Suggestion $suggestion
     */
    public function setSuggestion(Suggestion$suggestion)
    {
        $this->suggestion = $suggestion;
    }

    /**
     * @param AuthorInterface $author
     */
    public function setAuthor(AuthorInterface $author)
    {
        $this->author = $author;
    }

    /**
     * @param int $votes
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }
}
