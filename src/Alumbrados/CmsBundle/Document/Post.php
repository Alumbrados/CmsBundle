<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Alumbrados\CmsBundle\Document;

/**
 * Description of Post
 *
 * @author Martijn
 * @PHPCR\Document(referenceable=true)
 */

use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

class Post implements RouteReferrersReadInterface
{
    use ContentTrait;

    /**
     * @PHPCR\Date()
     */
    protected $date;

    /**
     * @PHPCR\PrePersist()
     */
    public function updateDate()
    {
        if (!$this->date) {
            $this->date = new \DateTime();
        }
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
}