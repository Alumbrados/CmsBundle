<?php

namespace Alumbrados\CmsBundle\Twig;

class AppExtension extends \Twig_Extension {

    public function getTests() {
        return [
            'instanceof' => new \Twig_Function_Method($this, 'isInstanceof')
        ];
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance) {
        return $var instanceof $instance;
    }

    public function getName() {
        return 'app_extension';
    }

}
