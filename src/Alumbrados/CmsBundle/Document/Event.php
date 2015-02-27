<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Alumbrados\CmsBundle\Document;

/**
 * Description of Event
 *
 * @author Martijn
 * @PHPCR\Document(referenceable=true)
 */

use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

class Event implements RouteReferrersReadInterface
{
    use ContentTrait;

    /**
     * @PHPCR\Date()
     */
    protected $dateFrom;

    /**
     * @PHPCR\PrePersist()
     */
    public function updateDateFrom()
    {
        if (!$this->dateFrom) {
            $this->dateFrom = new \DateTime();
        }
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTime $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }
    
    /**
     * @PHPCR\Date()
     */
    protected $dateTill;

    /**
     * @PHPCR\PrePersist()
     */
    public function updateDateTill()
    {
        if (!$this->dateTill) {
            $this->dateTill = new \DateTime();
        }
    }

    public function getDateTill()
    {
        return $this->dateTill;
    }

    public function setDateTill(\DateTime $dateTill)
    {
        $this->dateTill = $dateTill;
    }    
}