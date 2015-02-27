<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MenuItemController extends OrmController {

    protected $collectionName = 'menu_items';
    protected $entityName = 'menu_item';
    protected $entityHandle = '\Alumbrados\CmsBundle\Document\MenuNode';
    protected $entity = '\Alumbrados\CmsBundle\Document\MenuNode';
    protected $form = '\Alumbrados\CmsBundle\Form\MenuItemType';
    protected $nameProperty = 'getLabel';
    protected $idProperty = 'getUiid';

    /**
     * @Route("/menu_item/create", name="cms_menu_item_create")
     * @Template("CmsBundle:Default:create.html.twig")
     */
    public function createAction(Request $request) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem('menu', $this->get('router')->generate('cms_menu'));
        $breadcrumbs->addItem('create', $this->get('router')->generate('cms_' . $this->entityName . '_create'));

        $em = $this->get('doctrine_phpcr')->getManager();

        $entity = new $this->entity();
   
        $form = $this->createForm(new $this->form(), $entity, array(
            'action' => $this->generateUrl('cms_' . $this->entityName . '_create'),
            'method' => 'POST',
        ));

       
        
        $form->handleRequest($request);
        if ($form->isValid()) {
          
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cms_menu'));
        }

        return array(
            'section' => 'menu_item',
            'form' => $form->createView(),
            'item' => $entity
        );
    }

    /**
     * @Route("/menu_item/update/{id}", name="cms_menu_item_update")
     * @Template("CmsBundle:Default:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem('menu', $this->get('router')->generate('cms_menu'));
        $breadcrumbs->addItem('update', $this->get('router')->generate('cms_' . $this->entityName . '_update', array('id' => $id)));

        $em = $this->get('doctrine_phpcr')->getManager();

        $entity = $em->find('Alumbrados\CmsBundle\Document\MenuNode', $id);

        if ($entity) {
            $form = $this->createForm(new $this->form(), $entity, array(
                'action' => $this->generateUrl('cms_' . $this->entityName . '_update', array('id' => $id)),
                'method' => 'POST',
            ));

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('cms_menu'));
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
     * @Route("/menu_item/delete/{id}", name="cms_menu_item_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->get('doctrine_phpcr')->getManager();
        
        $entity = $em->find('Alumbrados\CmsBundle\Document\MenuNode', $id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();            
            return $this->redirect($this->generateUrl('cms_menu'));         
        }       
        throw $this->createNotFoundException();
    }

}
