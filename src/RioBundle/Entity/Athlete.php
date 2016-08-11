<?php

namespace RioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Athlete
 *
 * @ORM\Table(name="athlete")
 * @ORM\Entity(repositoryClass="RioBundle\Repository\AthleteRepository")
 */
class Athlete
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="Score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity="Epreuve", inversedBy="Athlete")
     * @ORM\JoinColumn(name="epreuve_id", referencedColumnName="id")
     */
    private $epreuve;

    /**
     * Set epreuve
     *
     * @param \RioBundle\Entity\Epreuve $epreuve
     *
     * @return Athlete
     */
    public function setEpreuve(\RioBundle\Entity\Epreuve $epreuve = null)
    {
        $this->epreuve = $epreuve;

        return $this;
    }




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
     * Set nom
     *
     * @param string $nom
     * @return Athlete
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return Athlete
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }



    /**
     * Get epreuve
     *
     * @return \RioBundle\Entity\Epreuve
     */
    public function getEpreuve()
    {
        return $this->epreuve;
    }


    /**
     * Set date
     *
     * @param integer $date
     * @return Athlete
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return integer
     */
    public function getDate()
    {
        return $this->date;
    }
    public function __toString() {
        return $this->image;
    }
}
