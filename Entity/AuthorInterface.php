<?php

namespace Cunningsoft\SuggestBundle\Entity;

interface AuthorInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function __toString();
}
