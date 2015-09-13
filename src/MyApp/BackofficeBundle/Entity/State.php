<?php

namespace MyApp\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="state")
 * @ORM\Entity(repositoryClass="MyApp\BackofficeBundle\Repository\StateRepository")
 */
class State {

    protected $produits;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nomState
     * @ORM\Column(name="nomState", type="string", length=255,nullable=true)
     *
     */
    private $nomState;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get nomState
     *
     * @return string
     */
    public function getNomState() {
        return $this->nomState;
    }

    /**
     * Set nomState
     *
     * @param string $nomState
     * @return State
     */
    public function setNomState($nomState) {
        $this->nomState = $nomState;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProduits() {
        return $this->produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits) {
        $this->produits = $produits;
    }

    public function __toString() {
        return $this->nomState . '';
    }

}
