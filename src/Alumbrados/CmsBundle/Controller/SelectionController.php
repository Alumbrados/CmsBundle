<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SelectionController extends OrmController {
    
    protected $collectionName = 'selections';
    protected $entity = '\Alumbrados\PlantBundle\Entity\Selection';
    protected $entityName = 'selection';
    protected $entityHandle = 'PlantBundle:Selection';
    protected $form = '\Alumbrados\PlantBundle\Form\SelectionType';
    protected $nameProperty = 'title';

    /**
     * @Route("/selection", name="cms_selection")
     * @Template("CmsBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request) {
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select(array('e'))
                ->from($this->entityHandle, 'e')
                ->orderBy('e.created_at');
        return $this->generateIndex($request, $queryBuilder);
    }

    /**
     * @Route("/selection/create", name="cms_selection_create")
     * @Template("CmsBundle:Default:create.html.twig")
     */
    public function createAction(Request $request) {
        return $this->generateCreate($request);
    }

    /**
     * @Route("/selection/update/{id}", name="cms_selection_update")
     * @Template("CmsBundle:Default:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        return $this->generateUpdate($request, $id);
    }

    /**
     * @Route("/selection/delete/{id}", name="cms_selection_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        return $this->generateDelete($request, $id);
    }

    protected function getFormType() {
        return new $this->form($this->generateUrl('cms_species_json'));
    }

}
