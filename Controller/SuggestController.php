<?php

namespace Cunningsoft\SuggestBundle\Controller;

use Cunningsoft\GladiatorenBundle\Controller\BaseController;
use Cunningsoft\SuggestBundle\Entity\AuthorInterface;
use Cunningsoft\SuggestBundle\Entity\Comment;
use Cunningsoft\SuggestBundle\Entity\Suggestion;
use Cunningsoft\SuggestBundle\Entity\Vote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/suggest")
 */
class SuggestController extends BaseController
{
    /**
     * @return array
     *
     * @Route("", name="cunningsoft_suggest_list")
     * @Template
     */
    public function listAction()
    {
        /** @var Suggestion[] $open */
        $open = $this->get('doctrine.orm.entity_manager')->getRepository('CunningsoftSuggestBundle:Suggestion')->findBy(array('isDone' => false));
        $votes = array();
        foreach ($open as $k => $suggestion) {
            $votes[$k] = $suggestion->getVotesOverall();
        }
        array_multisort($votes, SORT_DESC, $open);

        /** @var Suggestion[] $open */
        $done = $this->get('doctrine.orm.entity_manager')->getRepository('CunningsoftSuggestBundle:Suggestion')->findBy(array('isDone' => true));

        return array(
            'open' => $open,
            'done' => $done,
            'currentUser' => $this->getUser(),
        );
    }

    /**
     * @param Suggestion $suggestion
     *
     * @return array
     *
     * @Route("/{id}", name="cunningsoft_suggest_show", requirements={"id" = "\d+"})
     * @Template
     */
    public function showAction(Suggestion $suggestion)
    {
        return array(
            'suggestion' => $suggestion,
            'currentUser' => $this->getUser(),
            'votesLeft' => $this->container->getParameter('cunningsoft_suggest.number_of_votes') - $this->getVotesByAuthor($this->getUser()) + $suggestion->getVotesByAuthor($this->getUser()),
        );
    }

    /**
     * @return array|RedirectResponse
     *
     * @Route("/create", name="cunningsoft_suggest_create")
     * @Template
     */
    public function createAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            if ($request->get('title') == '') {
                throw new \Exception('title cannot be empty');
            }
            if ($request->get('description') == '') {
                throw new \Exception('description cannot be empty');
            }
            $suggestion = new Suggestion();
            $suggestion->setAuthor($this->getUser());
            $suggestion->setTitle($request->get('title'));
            $suggestion->setDescription($request->get('description'));
            $this->get('doctrine.orm.entity_manager')->persist($suggestion);
            $this->get('doctrine.orm.entity_manager')->flush();

            return $this->redirect($this->generateUrl('cunningsoft_suggest_show', array('id' => $suggestion->getId())));
        } else {
            return array();
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route("/{id}/comment", name="cunningsoft_suggest_comment")
     */
    public function commentAction(Suggestion $suggestion, Request $request)
    {
        $comment = new Comment();
        $comment->setSuggestion($suggestion);
        $comment->setAuthor($this->getUser());
        $comment->setMessage($request->get('comment'));
        $this->get('doctrine.orm.entity_manager')->persist($comment);
        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->redirect($this->generateUrl('cunningsoft_suggest_show', array('id' => $suggestion->getId())));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route("/{id}/vote", name="cunningsoft_suggest_vote")
     */
    public function voteAction(Suggestion $suggestion, Request $request)
    {
        if (!in_array($request->get('votes'), array(0, 1, 2, 3))) {
            throw new \Exception('invalid value as vote given: ' . $request->get('votes'));
        }
        if ($this->getVotesByAuthor($this->getUser()) - $suggestion->getVotesByAuthor($this->getUser()) + $request->get('votes') > $this->container->getParameter('cunningsoft_suggest.number_of_votes')) {
            throw new \Exception('too many votes assigned!');
        }
        $vote = $this->get('doctrine.orm.entity_manager')->getRepository('CunningsoftSuggestBundle:Vote')->findOneBy(array('suggestion' => $suggestion, 'author' => $this->getUser()));
        if ($request->get('votes') > 0) {
            if (empty($vote)) {
                $vote = new Vote();
                $vote->setSuggestion($suggestion);
                $vote->setAuthor($this->getUser());
                $this->get('doctrine.orm.entity_manager')->persist($vote);
            }
            $vote->setVotes($request->get('votes'));
        } elseif (!empty($vote)) {
            $this->get('doctrine.orm.entity_manager')->remove($vote);
        }
        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->redirect($this->generateUrl('cunningsoft_suggest_show', array('id' => $suggestion->getId())));
    }

    /**
     * @param AuthorInterface $author
     *
     * @return int
     */
    private function getVotesByAuthor(AuthorInterface $author)
    {
        /** @var Vote[] $userVotes */
        $userVotes = $this->get('doctrine.orm.entity_manager')->getRepository('CunningsoftSuggestBundle:Vote')->findBy(array('author' => $author));
        $overallVotes = 0;
        foreach ($userVotes as $vote) {
            $overallVotes += $vote->getVotes();
        }

        return $overallVotes;
    }
}
