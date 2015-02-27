<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Alumbrados\CmsBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Alumbrados\CmsBundle\Document\Page;

/**
 * Description of LoadPageData
 *
 * @author Martijn
 */
class LoadPageData implements FixtureInterface
{
    public function load(ObjectManager $dm)
    {
        if (!$dm instanceof DocumentManager) {
            $class = get_class($dm);
            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $parent = $dm->find(null, '/cms/pages');

        $rootPage = new Page();
        $rootPage->setTitle('main');
        $rootPage->setParentDocument($parent);
        $dm->persist($rootPage);

        $page = new Page();
        $page->setTitle('Home');
        $page->setParentDocument($rootPage);
        $page->setContent(<<<HERE
Welcome to the homepage of this really basic CMS.
HERE
        );
        $dm->persist($page);

        $page = new Page();
        $page->setTitle('About');
        $page->setParentDocument($rootPage);
        $page->setContent(<<<HERE
This page explains what its all about.
HERE
        );
        $dm->persist($page);

        $dm->flush();
    }
} 