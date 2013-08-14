<?php

namespace Cunningsoft\SuggestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Comment
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
     * @var Suggestion
     *
     * @ORM\ManyToOne(targetEntity="Suggestion", inversedBy="comments")
     */
    private $suggestion;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @return AuthorInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Suggestion $suggestion
     */
    public function setSuggestion(Suggestion $suggestion)
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
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
