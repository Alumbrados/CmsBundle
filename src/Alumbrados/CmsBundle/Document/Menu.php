<?php
namespace Alumbrados\CmsBundle\Document;

/**
 * Description of Page
 *
 * @author Martijn
 */

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
/**
 * Description of Menu
 *
 * @author Martijn
 */
/**
 * @PHPCR\Document(referenceable=true)
 */
class Menu extends \Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu {
    
    /**
     * @PHPCR\Uuid()
     */    
    protected $uuid;

    public function getUuid() {
        return $this->uuid;
    }
}
