<?php

namespace MyApp\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 */
class Produit
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $image;

    /**
     * @var float
     */
    private $nouveauprix;

    /**
     * @var float
     */
    private $ancienprix;

    /**
     * @var string
     */
    private $nomproduit;

    /**
     * @var string
     */
    private $detailsproduit;


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
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Produit
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get nouveauprix
     *
     * @return float
     */
    public function getNouveauprix()
    {
        return $this->nouveauprix;
    }

    /**
     * Set nouveauprix
     *
     * @param float $nouveauprix
     * @return Produit
     */
    public function setNouveauprix($nouveauprix)
    {
        $this->nouveauprix = $nouveauprix;

        return $this;
    }

    /**
     * Get ancienprix
     *
     * @return float
     */
    public function getAncienprix()
    {
        return $this->ancienprix;
    }

    /**
     * Set ancienprix
     *
     * @param float $ancienprix
     * @return Produit
     */
    public function setAncienprix($ancienprix)
    {
        $this->ancienprix = $ancienprix;

        return $this;
    }

    /**
     * Get nomproduit
     *
     * @return string
     */
    public function getNomproduit()
    {
        return $this->nomproduit;
    }

    /**
     * Set nomproduit
     *
     * @param string $nomproduit
     * @return Produit
     */
    public function setNomproduit($nomproduit)
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    /**
     * Get detailsproduit
     *
     * @return string
     */
    public function getDetailsproduit()
    {
        return $this->detailsproduit;
    }

    /**
     * Set detailsproduit
     *
     * @param string $detailsproduit
     * @return Produit
     */
    public function setDetailsproduit($detailsproduit)
    {
        $this->detailsproduit = $detailsproduit;

        return $this;
    }
}
