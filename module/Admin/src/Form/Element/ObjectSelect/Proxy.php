<?php

namespace Admin\Form\Element\ObjectSelect;

use DoctrineModule\Form\Element\Proxy as DoctrineProxy;
use RuntimeException;

/**
 * Class Proxy
 *
 * Extends doctrines Form\Element\Proxy to fix bug that only identifier can be loaded as option value
 *
 * @package Admin\Form\Element\ObjectSelect
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Proxy extends DoctrineProxy
{
    protected $value = false;

    protected $valueIsMethod = false;

    public function setOptions($options) : void
    {
        if (isset($options['value'])) {
            $this->value = $options['value'];
            unset($options['value']);
        }

        if (isset($options['value_is_method'])) {
            $this->valueIsMethod = $options['value_is_method'];
            unset($options['value_is_method']);
        }

        parent::setOptions($options);
    }
    /**
     * Load value options
     *
     * @throws RuntimeException
     * @return void
     */
    protected function loadValueOptions() : void
    {
        if (!($om = $this->objectManager)) {
            throw new RuntimeException('No object manager was set');
        }

        if (!($targetClass = $this->targetClass)) {
            throw new RuntimeException('No target class was set');
        }

        $metadata   = $om->getClassMetadata($targetClass);
        $identifier = $metadata->getIdentifierFieldNames();
        $objects    = $this->getObjects();
        $options    = array();

        if ($this->displayEmptyItem || empty($objects)) {
            $options[''] = $this->getEmptyItemLabel();
        }

        if (!empty($objects)) {
            foreach ($objects as $key => $object) {
                if (null !== ($generatedLabel = $this->generateLabel($object))) {
                    $label = $generatedLabel;
                } elseif ($property = $this->property) {
                    if ($this->isMethod == false && !$metadata->hasField($property)) {
                        throw new RuntimeException(
                            sprintf(
                                'Property "%s" could not be found in object "%s"',
                                $property,
                                $targetClass
                            )
                        );
                    }

                    $getter = 'get' . ucfirst($property);
                    if (!is_callable(array($object, $getter))) {
                        throw new RuntimeException(
                            sprintf('Method "%s::%s" is not callable', $this->targetClass, $getter)
                        );
                    }

                    $label = $object->{$getter}();
                } else {
                    if (!is_callable(array($object, '__toString'))) {
                        throw new RuntimeException(
                            sprintf(
                                '%s must have a "__toString()" method defined if you have not set a property'
                                . ' or method to use.',
                                $targetClass
                            )
                        );
                    }

                    $label = (string) $object;
                }

                if ($valueProperty = $this->value) {
                    if ($this->valueIsMethod == false && !$metadata->hasField($valueProperty)) {
                        throw new RuntimeException(
                            sprintf(
                                'Property "%s" could not be found in object "%s"',
                                $valueProperty,
                                $targetClass
                            )
                        );
                    }

                    $getter = ($this->valueIsMethod)? $valueProperty : 'get' . ucfirst($valueProperty);

                    if (!is_callable(array($object, $getter))) {
                        throw new RuntimeException(
                            sprintf('Method "%s::%s" is not callable', $this->targetClass, $getter)
                        );
                    }

                    $value = $object->{$getter}();
                } else {
                    if (count($identifier) > 1) {
                        $value = $key;
                    } else {
                        $value = current($metadata->getIdentifierValues($object));
                    }
                }

                $options[] = array('label' => $label, 'value' => $value);
            }
        }

        $this->valueOptions = $options;
    }
}