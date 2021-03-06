<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\CoreBundle\DataCollector;

use Pimcore\Http\Request\Resolver\PimcoreContextResolver;
use Pimcore\Version;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class PimcoreDataCollector extends DataCollector
{
    /**
     * @var PimcoreContextResolver
     */
    protected $contextResolver;

    public function __construct(PimcoreContextResolver $contextResolver)
    {
        $this->contextResolver = $contextResolver;
    }

    /**
     * @inheritDoc
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'version'    => Version::getVersion(),
            'revision'   => Version::getRevision(),
            'debug_mode' => (bool)\Pimcore::inDebugMode(),
            'dev_mode'   => defined('PIMCORE_DEVMODE') ? (bool)PIMCORE_DEVMODE : false,
            'context'    => $this->contextResolver->getPimcoreContext($request),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'pimcore';
    }

    /**
     * @return string|null
     */
    public function getContext()
    {
        return $this->data['context'];
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->data['version'];
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->data['revision'];
    }

    /**
     * @return bool
     */
    public function getDebugMode()
    {
        return $this->data['debug_mode'];
    }

    /**
     * @return bool
     */
    public function getDevMode()
    {
        return $this->data['dev_mode'];
    }
}
