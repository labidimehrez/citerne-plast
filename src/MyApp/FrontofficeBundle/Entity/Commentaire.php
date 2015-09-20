<?php

namespace MyApp\FrontofficeBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="commentaire")
 */
class Commentaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;
    
   /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */

    private $name;
    /**
     * @var string $email
     * @ORM\Column(name="email", type="string", length=255,nullable=true)
     *
     */

    private $email;
    /**
     * @var string $review
     * @ORM\Column(name="review", type="string", length=255,nullable=true)
     */
    private $review;
    /**
     * @var integer $rating
     *
     * @ORM\Column(name="rating", type="integer", length=255,nullable=true)
     */

    private $rating;   
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation",type="datetime",nullable=true)
     */
    private $datecreation;
    
    /**
     * @ORM\ManyToOne(targetEntity="MyApp\BackofficeBundle\Entity\Produit", inversedBy="commentaires")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     **/
    
    private $produit;
    
    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getReview() {
        return $this->review;
    }

    function getRating() {
        return $this->rating;
    }

    function getProduit() {
        return $this->produit;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setReview($review) {
        $this->review = $review;
    }

    function setRating($rating) {
        $this->rating = $rating;
    }

    function setProduit($produit) {
        $this->produit = $produit;
    }
    function getDatecreation() {
        return $this->datecreation;
    }

    function setDatecreation(\DateTime $datecreation) {
        $this->datecreation = $datecreation;
    }

       public function __construct()
    {
        $this->datecreation = new \DateTime();
    }

}