<?php

namespace Alumbrados\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpeciesController extends OrmController {

    protected $collectionName = 'species';
    protected $entity = '\Alumbrados\PlantBundle\Entity\Species';
    protected $entityName = 'species';
    protected $entityHandle = 'PlantBundle:Species';
    protected $form = '\Alumbrados\PlantBundle\Form\SpeciesType';
    protected $nameProperty = 'getFullTitle';

    /**
     * @Route("/species", name="cms_species")
     * @Template("CmsBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request) {
        $choices = array();
        $search = $request->query->get('search');
        if ($search && isset($search['id'])) {
            $em = $this->getDoctrine()->getManager();
        
            $entity = $em->find($this->entityHandle, $search['id']);

            if ($entity) {            
                $choices[$search['id']] = $entity->getFullTitle();
            }
        }
        
        /* @var $searchForm \Symfony\Component\Form\Formbuilder */
        $searchForm = $this->get('form.factory')
                ->createNamedBuilder('search', 'form', null, array(
                    'csrf_protection' => false,
                ))
                ->add('id', 'choice', array(
                    'choices' => $choices,
                    'label' => 'species',
                    'attr' => array(
                        'class' => 'selectpicker-live',
                        'data-live-search' => 'true',
                        'data-live-url' => $this->generateUrl('cms_species_json')
                    ),
                    'required' => false,
                ))
                ->setMethod('GET')
                
                ->add('actions', 'form_actions')->add('search', 'submit', ['label' => 'search', 'attr' => ['class' => 'btn-block']])
                ->getForm(); 

        
        $searchForm->handleRequest($request);
        if ($searchForm->isValid()) {
            $formData = $searchForm->getData();
            if (isset($formData['id'])) {
                return $this->redirect($this->generateUrl('cms_species_update', array('id' => $formData['id'])));
            }
        }
        
        $form = $this->get('form.factory')
                ->createNamedBuilder('filter', 'form', null, array('csrf_protection' => false))
                ->add('id', 'integer', array(
                    'label' => 'article_number',
                    'required' => false
                ))
                ->add('genus', 'entity', array(
                    'class' => 'Alumbrados\PlantBundle\Entity\Genus',
                    'property' => 'title',
                    'label' => 'genus',
                    'required' => false
                ))
                ->add('title', 'text', array(
                    'label' => 'name',
                    'required' => false
                ))
                ->add('name', 'text', array(
                    'label' => 'dutch_name',
                    'required' => false
                ))
//                ->add('synonym', 'text', array(
//                    'label' => 'synonym',
//                    'required' => false
//                ))
                ->add('variety', 'text', array(
                    'label' => 'variety',
                    'required' => false
                ))
                ->add('varietySynonym', 'text', array(
                    'label' => 'variety_synonym',
                    'required' => false
                ))
                ->setMethod('GET')
                ->add('actions', 'form_actions')->add('filter', 'submit', ['label' => 'filter', 'attr' => ['class' => 'btn-block']])
                ->getForm();

        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select(array('e', 'g'))
                ->from($this->entityHandle, 'e')
                ->innerJoin('e.genus', 'g')
                ->orderBy('g.title')
                ->addOrderBy('e.title')
                ->addOrderBy('e.variety')
                ->addOrderBy('e.name');

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $formData = $form->getData();
            
            if ($formData['id']) {
                $queryBuilder->andWhere('e.id = :id')->setParameter('id', $formData['id']);
            }
            if ($formData['genus']) {
                $queryBuilder->andWhere('g.id = :genus_id')->setParameter('genus_id', $formData['genus']);
            }
            if ($formData['title']) {
                $queryBuilder->andWhere('e.title LIKE :title')->setParameter('title', '%' . $formData['title'] . '%');
            }
            if ($formData['name']) {
                $queryBuilder->andWhere('e.name LIKE :name')->setParameter('name', '%' . $formData['name'] . '%');
            }    
//            if ($formData['synonym']) {
//                $queryBuilder->andWhere('e.synonym LIKE :synonym')->setParameter('synonym', '%' . $formData['synonym'] . '%');
//            }  
            if ($formData['variety']) {
                $queryBuilder->andWhere('e.variety LIKE :variety')->setParameter('variety', '%' . $formData['variety'] . '%');
            }  
            if ($formData['varietySynonym']) {
                $queryBuilder->andWhere('e.varietySynonym LIKE :varietySynonym')->setParameter('varietySynonym', '%' . $formData['varietySynonym'] . '%');
            }              
        }

        $data = $this->generateIndex($request, $queryBuilder);

        $data['filter'] = $form->createView();
        $data['search'] = $searchForm->createView();
        
        $data['fields'] = array(
            array(
                'field' => 'id',
                'label' => 'article_number',
                'class' => 'number'
            ),
            array(
                'field' => 'genus',
                'label' => 'genus',
                'class' => 'text'
            ),            
            array(
                'field' => 'speciesTitle',
                'label' => 'name',
                'class' => ''
            ),
            array(
                'field' => 'publish',
                'label' => 'publish',
                'class' => 'boolean',
                'type' => 'boolean'
            )            
        );

        return $data;
    }

    /**
     * @Route("/species/prices", name="cms_species_prices")
     * @Template("CmsBundle:Default:index.html.twig")
     */
    public function pricesAction(Request $request) {
        $data = $this->indexAction($request);
        $data['fields'] = array(
            array(
                'field' => 'id',
                'label' => 'article_number',
                'class' => 'number'
            ),
            array(
                'field' => 'genus',
                'label' => 'genus',
                'class' => 'text'
            ),            
            array(
                'field' => 'speciesTitle',
                'label' => 'name',
                'class' => ''
            ),
            array(
                'field' => 'priceP9',
                'label' => 'price_p9',
                'class' => 'number',
                'type' => 'money'
            ),     
            array(
                'field' => 'priceP11',
                'label' => 'price_p11',
                'class' => 'number',
                'type' => 'money'
            ),
            array(
                'field' => 'priceC',
                'label' => 'price_c',
                'class' => 'number',
                'type' => 'money'
            )               
        );  
        return $data;
    }    
    
    /**
     * @Route("/species/stock", name="cms_species_stock")
     * @Template("CmsBundle:Default:index.html.twig")
     */
    public function stockAction(Request $request) {
        $data = $this->indexAction($request);
        $data['fields'] = array(
            array(
                'field' => 'id',
                'label' => 'article_number',
                'class' => 'number'
            ),
            array(
                'field' => 'genus',
                'label' => 'genus',
                'class' => 'text'
            ),            
            array(
                'field' => 'speciesTitle',
                'label' => 'name',
                'class' => ''
            ),
            array(
                'field' => 'stockP9',
                'label' => 'stock_p9',
                'class' => 'number',
            ),
            array(
                'field' => 'stockP11',
                'label' => 'stock_p11',
                'class' => 'number',
            ),
            array(
                'field' => 'stockC',
                'label' => 'stock_c',
                'class' => 'number',
            )            
        );  
        return $data;
    }       
    
    /**
     * @Route("/species/create", name="cms_species_create")
     * @Template("CmsBundle:Species:create.html.twig")
     */
    public function createAction(Request $request) {
        return $this->generateCreate($request);
    }
    
    /**
     * @Route("/species/json", name="cms_species_json")
     * @Template("CmsBundle:Species:create.html.twig")
     */
    public function jsonAction(Request $request) {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        
        $words = explode(' ', $request->request->get('q'));
        
        $qb->select(array('e', 'g'))
            ->from($this->entityHandle, 'e')
            ->innerJoin('e.genus', 'g')
            ->orderBy('g.title')
            ->addOrderBy('e.title')
            ->addOrderBy('e.variety')
            ->addOrderBy('e.name')
            ->setMaxResults(100);
            
        foreach ($words as $i => $word) {
            if ($i == 0) {
                $where = 'where';
            }
            else {
                $where = 'andWhere';
            }
            
            $qb->setParameter($i, '%' . $word . '%')
            ->$where(
                $qb->expr()->orX(
                    $qb->expr()->like('g.title', '?' . $i),
                    $qb->expr()->like('e.title', '?' . $i),
                    $qb->expr()->like('e.variety', '?' . $i),
                    $qb->expr()->like('e.name', '?' . $i),
                    $qb->expr()->like('e.varietySynonym', '?' . $i)
                )
            );
        }
        $result = $qb->getQuery()->getResult();
        $data = array();
        foreach ($result as $item) {
            $data[] = array(
                'value' => $item->getId(),
                'text' => $item->getFullTitle(),
                'route' => $this->generateUrl('cms_species_update', array('id' => $item->getId())) 
            );
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($data);
    }    

    /**
     * @Route("/species/update/{id}", name="cms_species_update")
     * @Template("CmsBundle:Species:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        return $this->generateUpdate($request, $id);
    }

    /**
     * @Route("/species/delete/{id}", name="cms_species_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id) {
        return $this->generateDelete($request, $id);
    }
    /**
     * @Route("/species/label", name="cms_species_label")
     * @Template()
     */    
    public function labelAction() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select(array('e', 'g'))
            ->from($this->entityHandle, 'e')
            ->innerJoin('e.genus', 'g')
            ->orderBy('g.title')
            ->where('e.publish = 1')
            ->addOrderBy('e.title')
            ->addOrderBy('e.variety')
            ->addOrderBy('e.name')
            ->setMaxResults(100);        
        
        return array(
            'entities' => $qb->getQuery()->getResult()
        );
    }    
    
}
