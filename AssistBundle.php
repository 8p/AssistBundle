<?php

namespace EightPoints\Bundle\AssistBundle;

use       Symfony\Component\HttpKernel\Bundle\Bundle,
          Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AssistBundle
 *
 * @package   EightPoints\Bundle\AssistBundle
 *
 * @copyright 8points IT
 * @author    Florian Preusner
 *
 * @version   1.0
 * @since     2013-10
 */
class AssistBundle extends Bundle {

    /**
     * Build AssistBundle
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   ContainerBuilder $container
     * @return  void
     */
    public function build(ContainerBuilder $container) {

        parent::build($container);
    } // end: build
} // end: AssistBundle
