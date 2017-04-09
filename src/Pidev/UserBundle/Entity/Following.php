<?php
/**
 * Created by PhpStorm.
 * User: xagta1
 * Date: 08/04/2017
 * Time: 17:42
 */

namespace Pidev\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 *Follwing
 *
 *@ORM\Table(name="Following", indexes={@ORM\Index(name="Follower", columns={"Follower"}), @ORM\Index(name="Followed", columns={"Followed"})})
 *@ORM\Entity
 **/

class Following
{
/**
 * @var integer
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="IDENTITY")
 *
 **/

private $id ;



    /**
     * @var \Pidev\UserBundle\Entity\Membre
     * @ORM\Column(name="Follower",type="integer")
     * @ORM\ManyToOne(targetEntity="Pidev\UserBundle\Entity\Membre")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="Follower",referencedColumnName="id")
     * })
     */
private $Follower ;


    /**
     * @var \Pidev\UserBundle\Entity\Membre
     * @ORM\Column(name="Followed",type="integer")
     * @ORM\ManyToOne(targetEntity="Pidev\UserBundle\Entity\Membre")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="Followed",referencedColumnName="id")
     * })
     */
private $Followed ;



}