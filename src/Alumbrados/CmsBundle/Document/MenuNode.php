<?php
namespace Alumbrados\CmsBundle\Document;

/**
 * Description of Page
 *
 * @author Martijn
 */

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
/**
 * Description of MenuNode
 *
 * @author Martijn
 */
/**
 * @PHPCR\Document(referenceable=true)
 */
class MenuNode extends \Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode {
    
    /**
     * @PHPCR\Uuid()
     */    
    protected $uuid;

    public function getUuid() {
        return $this->uuid;
    }
}
