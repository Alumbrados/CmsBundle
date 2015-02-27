<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SubMenuController extends OrmController {

    protected $collectionName = 'sub_menus';
    protected $entityName = 'sub_menu';
    protected $entityHandle = '\Alumbrados\CmsBundle\Document\Menu';
    protected $entity = '\Alumbrados\CmsBundle\Document\Menu';
    protected $form = '\Alumbrados\CmsBundle\Form\SubMenuType';
    protected $nameProperty = 'getLabel';
    protected $idProperty = 'getName';

    

    /**
     * @Route("/sub_menu/create", name="cms_sub_menu_create")
     * @Template("CmsBundle:Default:create.html.twig")
     */
    public function createAction(Request $request) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem('menu', $this->get('router')->generate('cms_menu'));
        $breadcrumbs->addItem('create', $this->get('router')->generate('cms_' . $this->entityName . '_create'));

        $em = $this->get('doctrine_phpcr')->getManager();

        $menuParent = $em->find(null, '/cms/menu');
        $entity = new $this->entity();
        
        $form = $this->createForm(new $this->form($menuParent), $entity, array(
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
            'section' => 'menu',
            'form' => $form->createView(),
            'item' => $entity
        );
    }

    /**
     * @Route("/sub_menu/update/{id}", name="cms_sub_menu_update")
     * @Template("CmsBundle:Default:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem('menu', $this->get('router')->generate('cms_menu'));
        $breadcrumbs->addItem('update', $this->get('router')->generate('cms_' . $this->entityName . '_update', array('id' => $id)));

        $em = $this->get('doctrine_phpcr')->getManager();

        $entity = $em->find('Alumbrados\CmsBundle\Document\Menu', '/cms/menu/' . $id);

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
     * @Route("/sub_menu/delete/{id}", name="cms_sub_menu_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        return $this->generateDelete($request, $id);
    }

}
