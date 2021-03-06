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
 * @category   Pimcore
 * @package    Object
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Model\DataObject\ImportResolver;

use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Concrete;

class Id extends AbstractResolver
{
    /**
     * @param $parentId
     * @param $rowData
     *
     * @return static
     *
     * @throws \Exception
     */
    public function resolve($parentId, $rowData)
    {
        if (!is_null($this->idIdx)) {
            $id = $rowData[$this->idIdx];

            $object = Concrete::getById($id);
            if (!$object) {
                throw new \Exception('Could not resolve object with id ' . $id);
            }

            $classDefinition = ClassDefinition::getById($this->config->classId);
            $className = 'Pimcore\\Model\\DataObject\\' . ucfirst($classDefinition->getName());

            if (!$object instanceof $className) {
                throw new \Exception('Class mismatch for ID ' . $id);
            }

            $parent = $object->getParent();
            if (!$parent->isAllowed('create')) {
                throw new \Exception('no permission to overwrite object with id ' . $id);
            }

            return $object;
        }
    }
}
