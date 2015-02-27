<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends OrmController {

    protected $collectionName = 'pages';
    protected $entityName = 'page';
    protected $entityHandle = 'Alumbrados\CmsBundle\Document\Page';
    protected $entity = '\Alumbrados\CmsBundle\Document\Page';
    protected $form = '\Alumbrados\CmsBundle\Form\PageType';
    protected $idProperty = 'uuid';
    protected $nameProperty = 'name';

    /**
     * @Route("/page", name="cms_page")
     * @Template("CmsBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request) {
        $qb = $this->get('doctrine_phpcr')
                ->getManager()
                ->createQueryBuilder();
        
        $list = $qb
                ->fromDocument($this->entityHandle, 'e')
                ->getQuery()
                ->execute();
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));

        
        $entity = new $this->entity();
        return array(
            'section' => $this->collectionName,
            'list' => $list,
            'sortable' => ($entity instanceof Sortable),
            'routable' => ($entity instanceof \Symfony\Cmf\Component\Routing\RouteReferrersReadInterface),
            'entity_name' => $this->entityName,
            'name_property' => $this->nameProperty,
            'id_property' => $this->idProperty,
            'allow_create' => $this->allowCreate,
            'allow_update' => $this->allowUpdate,
            'allow_delete' => $this->allowDelete
         );        
    }

    /**
     * @Route("/page/create", name="cms_page_create")
     * @Template("CmsBundle:Default:create.html.twig")
     */
    public function createAction(Request $request) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));
        $breadcrumbs->addItem('create', $this->get('router')->generate('cms_' . $this->entityName . '_create'));

        $em = $this->get('doctrine_phpcr')->getManager();
        
        $entity = new $this->entity();
        
        $pageParent = $em->find(null, '/cms/pages');
        
        $form = $this->createForm(new $this->form(), $entity, array(
            'action' => $this->generateUrl('cms_' . $this->entityName . '_create'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setParentDocument($pageParent);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cms_' . $this->entityName));
        }            

        return array(
            'section' => 'pages',
            'form' => $form->createView(),
            'item' => $entity
        );        
    }

    /**
     * @Route("/page/update/{id}", name="cms_page_update")
     * @Template("CmsBundle:Default:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));
        $breadcrumbs->addItem('update', $this->get('router')->generate('cms_' . $this->entityName . '_update', array('id' => $id)));

        $em = $this->get('doctrine_phpcr')->getManager();
        
        $entity = $em->find($this->entityHandle, $id);

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
                'section' => 'pages',
                'form' => $form->createView(),
                'item' => $entity,
                'name_property' => $this->nameProperty,   
                'id_property' => $this->idProperty, 
            );
        }
        throw $this->createNotFoundException();
    }

    /**
     * @Route("/page/delete/{id}", name="cms_page_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->get('doctrine_phpcr')->getManager();
        
        $entity = $em->find($this->entityHandle, $id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();            
            return $this->redirect($this->generateUrl('cms_' . $this->entityName));         
        }       
        throw $this->createNotFoundException();
    }

}
