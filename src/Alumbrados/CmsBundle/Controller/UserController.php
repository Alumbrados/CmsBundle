<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends OrmController {
    
    protected $collectionName = 'users';
    protected $entity = '\Alumbrados\CmsBundle\Entity\User';
    protected $entityName = 'user';
    protected $entityHandle = 'CmsBundle:User';
    protected $form = '\Alumbrados\CmsBundle\Form\UserType';
    protected $nameProperty = 'getUsername';

    /**
     * @Route("/user", name="cms_user")
     * @Template("CmsBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request) {
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select(array('e'))
                ->from($this->entityHandle, 'e')
                ->orderBy('e.username');
        return $this->generateIndex($request, $queryBuilder);
    }

    /**
     * @Route("/user/create", name="cms_user_create")
     * @Template("CmsBundle:Default:create.html.twig")
     */
    public function createAction(Request $request) {
        return $this->generateCreate($request);
    }

    /**
     * @Route("/user/update/{id}", name="cms_user_update")
     * @Template("CmsBundle:Default:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        return $this->generateUpdate($request, $id);
    }

    /**
     * @Route("/user/delete/{id}", name="cms_user_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        return $this->generateDelete($request, $id);
    }

}
