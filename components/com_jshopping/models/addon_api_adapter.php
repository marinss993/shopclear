<?php
    /*
    * @version      1.0.3 01.11.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_adapter extends JshoppingModelAddon_api {

        /**
         * @throws AddonApiException
         */
        public function checkArrayArg(array $array, string $type, string $argument) {
            if (!AddonApi::getInst()->getModel('datatype')->checkArray($array, $type)) {
                throw new AddonApiException_request(
                    7,
                    '
                        Each element of argument \'' . $argument . '\'
                        must be of the type ' . $type . '
                    '
                );
            }
        }

        public function getAvailableTasks(JshoppingControllerAddon_api_subcontroller $subcontroller): array {
            $res       = [];
            $classname = get_class($subcontroller);
            foreach ((new ReflectionClass($subcontroller))->getMethods() as $method) {
                if ($method->isPublic() && $method->class == $classname) {
                    $res[] = $method->getName();
                }
            }
            return $res;
        }

        public function getBasicData(JInput $jinp = null, bool $cashed = true): array {
            static $res;
            if ($res && $cashed) {
                return $res;
            }
            $jinp       = $jinp ? $jinp : $this->getJInput();
            return $res = [
                'section' => strtolower(trim($jinp->getCmd('section'))),
                'task'    => trim($jinp->getCmd('task')),
                'args'    => $this->typifyArgs($jinp->get('args', [], 'ARRAY')),
                'format'  => strtolower(
                    trim(
                        $jinp->getCmd(
                            'format',
                            AddonApi::getInst()->getParam('reply[default_format]')
                        )
                    )
                )
            ];
        }

        public function getDefaultResult(
            JshoppingControllerAddon_api_subcontroller $subcontroller,
            string                                     $task
        ) {
            $res = $subcontroller->getDefaultResult();
            if ($res !== null) {
                return $res;
            }
            $reflection_type = (string) (new ReflectionMethod($subcontroller, $task))->getReturnType();
            if (!$reflection_type) {
                return null;
            }
            switch ($reflection_type) {
                case 'array':
                    return [];
                case 'bool':
                    return false;
                case 'int':
                case 'float':
                    return 0;
                case 'string':
                    return '';
            }
            return null;
        }

        public function getJInput(): JInput {
            return JFactory::getApplication()->input->post;
        }

        /**
         * @throws AddonApiException
         */
        public function getSubcontroller(string $section): JshoppingControllerAddon_api_subcontroller {
            $path = $this->getSubcontrollerPath($section);
            if (!file_exists($path)) {
                throw new AddonApiException_request(4, ' \'' . $section . '\'');
            }
            require_once $path;
            $name = $this->getSubcontrollerName($section);
            return new $name;
        }

        public function getSubcontrollerName(string $section): string {
            return (
                $this->isAddonSection($section)
                ? (
                    'JshoppingController' .
                    ucfirst(str_replace('__', '_Addon_api_', $section))
                )
                : (
                    'JshoppingControllerAddon_api_subcontroller_' . $section
                )
            );
        }

        public function getSubcontrollerPath(string $section): string {
            $addon = AddonApi::getInst();
            return JPath::clean(
                $this->isAddonSection($section)
                ? (
                    $addon->getParam('dirs_pathes[controllers]') .
                    str_replace('__', '_' . $addon->getAlias() . '_', $section) .
                    '.php'
                )
                : (
                    $addon->getParam('dirs_pathes[subcontrollers]') .
                    $section . '.php'
                )
            );
        }

        public function isAddonSection(string $section): bool {
            return strpos($section, 'addon_') === 0 && strpos($section, '__') > 0;
        }

        /**
         * @throws AddonApiException
         */
        public function prepareArgs(
            JshoppingControllerAddon_api_subcontroller $subcontroller,
            string                                     $task,
            array                                      $args
        ): array {
            $res   = [];
            $model = AddonApi::getInst()->getModel('datatype');
            /* @var $rp ReflectionParameter */
            foreach ((new ReflectionMethod($subcontroller, $task))->getParameters() as $rp) {
                $rp_name = $rp->getName();
                if (isset($args[$rp_name])) {
                    $rp_type  = (string) $rp->getType();
                    $arg_type = $model->getType($args[$rp_name]);
                    /* check argument type */
                    if ($rp_type !== $arg_type) {
                        throw new AddonApiException_request(
                            7,
                            trim('
                                . Argument \'' . $rp_name . '\'
                                must be of the type ' . $rp_type . ',
                                ' . $arg_type . ' given
                            ')
                        );
                    }
                    $res[] = $args[$rp_name];
                } else {
                    /* check required arguments */
                    try {
                        $res[] = $rp->getDefaultValue();
                    } catch (Exception $e) {
                        throw new AddonApiException_request(
                            6,
                            '. Task \'' . $task . '\' requires \'' . $rp_name . '\' argument'
                        );
                    }
                }
            }
            return $res;
        }

        public function typifyArgs(array $args): array {
            return array_map(
                $closure = function($el) use(&$closure) {
                    if (is_numeric($el)) {
                        return 1 * $el;
                    }
                    if (is_string($el)) {
                        return trim($el);
                    }
                    if (is_array($el)) {
                        return array_map($closure, $el);
                    }
                    return $el ? 1 : 0;
                },
                $args
            );
        }

    }
