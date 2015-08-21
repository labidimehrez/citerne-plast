<?php

namespace MyApp\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * State
 */
class State
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nomState;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get nomState
     *
     * @return string
     */
    public function getNomState()
    {
        return $this->nomState;
    }

    /**
     * Set nomState
     *
     * @param string $nomState
     * @return State
     */
    public function setNomState($nomState)
    {
        $this->nomState = $nomState;

        return $this;
    }
}
