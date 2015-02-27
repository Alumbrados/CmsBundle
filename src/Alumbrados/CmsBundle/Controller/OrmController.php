<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use Gedmo\Sortable\Sortable;

class OrmController extends Controller {
    
    protected $collectionName = '';
    protected $entity = '';
    protected $entityName = '';
    protected $entityHandle = '';
    protected $form = '';
    protected $nameProperty = 'getTitle';
    protected $idProperty = 'getId';
    
    protected $allowCreate = true;
    protected $allowUpdate = true;
    protected $allowDelete = true;
    
    protected function generateIndex(Request $request, \Doctrine\ORM\QueryBuilder $queryBuilder) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new \Pagerfanta\Pagerfanta($adapter);
        $pager->setMaxPerPage(25);
        
        if ($request->query->get('page')) {
            $pager->setCurrentPage($request->query->get('page'));
        }
        
        $entity = new $this->entity();
        return array(
            'section' => $this->collectionName,
            'list' => $queryBuilder
                ->setFirstResult(($pager->getCurrentPageOffsetStart()-1 < 0 ? 0 : $pager->getCurrentPageOffsetStart()-1))
                ->setMaxResults($pager->getMaxPerPage())
                ->getQuery()
                ->getResult(),
            'pager' => $pager,
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

    protected function generateCreate(Request $request) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));
        $breadcrumbs->addItem('create', $this->get('router')->generate('cms_' . $this->entityName . '_create'));

        $em = $this->getDoctrine()->getManager();
        
        $entity = new $this->entity();
        
        $form = $this->getForm($request, $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cms_' . $this->entityName, array('filter' => $request->query->get('filter'), 'page' => $request->query->get('page'))));
        }            

        return array(
            'section' => $this->collectionName,
            'form' => $form->createView(),
            'item' => $entity
        );
    }

    protected function generateUpdate(Request $request, $id) {
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem('home', $this->get('router')->generate('cms_dashboard'));
        $breadcrumbs->addItem($this->collectionName, $this->get('router')->generate('cms_' . $this->entityName));
        $breadcrumbs->addItem('update', $this->get('router')->generate('cms_' . $this->entityName . '_update', array('id' => $id)));

        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->find($this->entityHandle, $id);

        if ($entity) {

            $form = $this->getForm($request, $entity, $id);
            
            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('cms_' . $this->entityName, array('filter' => $request->query->get('filter'), 'page' => $request->query->get('page'))));
            }            

            return array(
                'section' => $this->collectionName,
                'form' => $form->createView(),
                'item' => $entity,
                'name_property' => $this->nameProperty,   
                'id_property' => $this->idProperty, 
            );
        }
        throw $this->createNotFoundException();
    }

    protected function generateDelete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->find($this->entityHandle, $id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();            
            return $this->redirect($this->generateUrl('cms_' . $this->entityName));           
        }       
        throw $this->createNotFoundException();

    }
    
    protected function getForm(Request $request, $entity, $id = null) {
        if ($id) {
            $form = $this->createForm($this->getFormType(), $entity, array(
                'action' => $this->generateUrl('cms_' . $this->entityName . '_update', array('id' => $id, 'filter' => $request->query->get('filter'), 'page' => $request->query->get('page'))),
                'method' => 'POST',
            ));            
        }
        else {
            $form = $this->createForm($this->getFormType(), $entity, array(
                'action' => $this->generateUrl('cms_' . $this->entityName . '_create', array('filter' => $request->query->get('filter'), 'page' => $request->query->get('page'))),
                'method' => 'POST',
            ));
        }
        
        return $form;
    }
    protected function getFormType() {
        return new $this->form();
    }
}
