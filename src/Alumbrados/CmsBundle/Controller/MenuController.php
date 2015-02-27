<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MenuController extends OrmController {

    protected $collectionName = 'menus';
    protected $entityName = 'menu';
    protected $entityHandle = '\Alumbrados\CmsBundle\Document\Menu';
    protected $entity = '\Alumbrados\CmsBundle\Document\Menu';
    protected $form = '\Alumbrados\CmsBundle\Form\MenuType';
    protected $nameProperty = 'getLabel';
    protected $idProperty = 'getUuid';

/**
     * @Route("/menu", name="cms_menu")
     * @Template("CmsBundle:Menu:index.html.twig")
     */
    public function indexAction() {

        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));

        $list = $this->get('doctrine_phpcr')->getManager()->find(null, '/cms/menu');
        
        return array(
            'section' => 'menu',
            'sortable' => false,
            'allow_create' => true,
            'allow_update' => true,
            'allow_delete' => true,
            'list' => $list,
            'entity_name' => 'menu',
            'name_property' => $this->nameProperty,
            'id_property' => $this->idProperty,
        );
    }    
    
    /**
     * @Route("/menu/create", name="cms_menu_create")
     * @Template("CmsBundle:Default:create.html.twig")
     */
    public function createAction(Request $request) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));
        $breadcrumbs->addItem('create', $this->get('router')->generate('cms_' . $this->entityName . '_create'));

        $em = $this->get('doctrine_phpcr')->getManager();

        $menuParent = $em->find(null, '/cms/menu');
        $entity = new $this->entity();
        $entity->setParentDocument($menuParent);
        $form = $this->createForm(new $this->form($menuParent), $entity, array(
            'action' => $this->generateUrl('cms_' . $this->entityName . '_create'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cms_' . $this->entityName));
        }

        return array(
            'section' => 'menu',
            'form' => $form->createView(),
            'item' => $entity
        );
    }

    /**
     * @Route("/menu/update/{id}", name="cms_menu_update")
     * @Template("CmsBundle:Default:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));
        $breadcrumbs->addItem('update', $this->get('router')->generate('cms_' . $this->entityName . '_update', array('id' => $id)));

        $em = $this->get('doctrine_phpcr')->getManager();

        $entity = $em->find('Alumbrados\CmsBundle\Document\Menu', $id);

        if ($entity) {
            $form = $this->createForm(new $this->form(), $entity, array(
                'action' => $this->generateUrl('cms_' . $this->entityName . '_update', array('id' => $id)),
                'method' => 'POST',
            ));

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('cms_' . $this->entityName));
            }

            return array(
                'section' => 'menu',
                'form' => $form->createView(),
                'item' => $entity,
                'name_property' => $this->nameProperty,
                'id_property' => $this->idProperty,                
            );
        }
        throw $this->createNotFoundException();
    }

    /**
     * @Route("/menu/delete/{id}", name="cms_menu_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->get('doctrine_phpcr')->getManager();
        
        $entity = $em->find('Alumbrados\CmsBundle\Document\Menu', $id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();            
            return $this->redirect($this->generateUrl('cms_menu'));         
        }       
        throw $this->createNotFoundException();
    }

}
