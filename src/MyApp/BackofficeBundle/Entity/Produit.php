<?php

namespace MyApp\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="MyApp\BackofficeBundle\Repository\ProduitRepository")
 */
class Produit {

    /**
     * @ORM\ManyToOne(targetEntity="MyApp\BackofficeBundle\Entity\State")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id",nullable=true)
     */
    protected $state;

    /**
     * @ORM\ManyToOne(targetEntity="MyApp\BackofficeBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id",nullable=true)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="MyApp\BackofficeBundle\Entity\Departement")
     * @ORM\JoinColumn(name="departement_id", referencedColumnName="id",nullable=true)
     */
    protected $departement;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255,nullable=true)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="qteStock", type="integer", length=255,nullable=true)
     */
    private $qteStock;

    /**
     * @var integer
     *
     * @ORM\Column(name="qteCommand", type="integer", length=255,nullable=true)
     */
    private $qteCommand;

    /**
     * @var float
     *
     * @ORM\Column(name="nouveauprix", type="float", length=255,nullable=true)
     */
    private $nouveauprix;

    /**
     * @var float
     *
     * @ORM\Column(name="ancienprix", type="float", length=255,nullable=true)
     */
    private $ancienprix;

    /**
     * @var string
     *
     * @ORM\Column(name="nomproduit", type="string", length=255,nullable=true)
     */
    private $nomproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255,nullable=true)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="detailsproduit", type="string", length=255,nullable=true)
     */
    private $detailsproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255,nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Produit
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get nouveauprix
     *
     * @return float
     */
    public function getNouveauprix() {
        return $this->nouveauprix;
    }

    /**
     * Set nouveauprix
     *
     * @param float $nouveauprix
     * @return Produit
     */
    public function setNouveauprix($nouveauprix) {
        $this->nouveauprix = $nouveauprix;

        return $this;
    }

    /**
     * Get ancienprix
     *
     * @return float
     */
    public function getAncienprix() {
        return $this->ancienprix;
    }

    /**
     * Set ancienprix
     *
     * @param float $ancienprix
     * @return Produit
     */
    public function setAncienprix($ancienprix) {
        $this->ancienprix = $ancienprix;

        return $this;
    }

    /**
     * Get nomproduit
     *
     * @return string
     */
    public function getNomproduit() {
        return $this->nomproduit;
    }

    /**
     * Set nomproduit
     *
     * @param string $nomproduit
     * @return Produit
     */
    public function setNomproduit($nomproduit) {
        $this->nomproduit = $nomproduit;
        $this->setSlug($this->nomproduit);
        return $this;
    }

    /**
     * Get detailsproduit
     *
     * @return string
     */
    public function getDetailsproduit() {
        return $this->detailsproduit;
    }

    /**
     * Set detailsproduit
     *
     * @param string $detailsproduit
     * @return Produit
     */
    public function setDetailsproduit($detailsproduit) {
        $this->detailsproduit = $detailsproduit;

        return $this;
    }

    /**
     * @return int
     */
    public function getQteStock() {
        return $this->qteStock;
    }

    /**
     * @param int $qteStock
     */
    public function setQteStock($qteStock) {
        $this->qteStock = $qteStock;
    }

    /**
     * @return int
     */
    public function getQteCommand() {
        return $this->qteCommand;
    }

    /**
     * @param int $qteCommand
     */
    public function setQteCommand($qteCommand) {
        $this->qteCommand = $qteCommand;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state) {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getDepartement() {
        return $this->departement;
    }

    /**
     * @param mixed $departement
     */
    public function setDepartement($departement) {
        $this->departement = $departement;
    }

    function getMarque() {
        return $this->marque;
    }

    function setMarque($marque) {
        $this->marque = $marque;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }
    
    function getSlug() {
        return $this->slug;
    }

    function setSlug($slug) {
        $this->slug = $this->slugify($slug);
    }

        public function slugify($text) {       
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);// replace non letter or digits by -      
        $text = trim($text, '-');// trim      
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // transliterate
        }     
        $text = strtolower($text); // lowercase       
        $text = preg_replace('#[^-\w]+#', '', $text);// remove unwanted characters
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

}
