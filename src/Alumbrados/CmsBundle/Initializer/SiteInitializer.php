<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteInitializer
 *
 * @author Martijn
 */

namespace Alumbrados\CmsBundle\Initializer;

use Doctrine\Bundle\PHPCRBundle\Initializer\InitializerInterface;
use PHPCR\Util\NodeHelper;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Alumbrados\CmsBundle\Document\Site;

class SiteInitializer implements InitializerInterface
{
    private $basePath;

    public function __construct($basePath = '/cms')
    {
        $this->basePath = $basePath;
    }

    public function init(ManagerRegistry $registry)
    {
        $dm = $registry->getManager();
        if ($dm->find(null, $this->basePath)) {
            return;
        }

        $site = new Site();
        $site->setId($this->basePath);
        $dm->persist($site);
        $dm->flush();

        $session = $registry->getConnection();

        NodeHelper::createPath($session, $this->basePath . '/pages');
        NodeHelper::createPath($session, $this->basePath . '/posts');
        NodeHelper::createPath($session, $this->basePath . '/events');
        NodeHelper::createPath($session, $this->basePath . '/routes');

        $session->save();
    }

    public function getName()
    {
        return 'Site initializer';
    }
}