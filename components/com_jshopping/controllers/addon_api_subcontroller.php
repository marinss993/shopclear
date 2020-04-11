<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller {

        private
            $code           = 1,
            $note           = '',
            $default_result = null;

        public function __construct() {}

        protected function getModel(string $name = ''): jshopBase {
            $res = AddonApi::getInst()->getModel(
                $name
                    ? $name
                    : preg_replace(
                        '/^' . __CLASS__ . '_/',
                        '',
                        get_class($this)
                    )
            );
            if ($res === false) {
                $res = JSFactory::getModel(
                    strtolower(
                        trim(
                            $name
                                ? $name
                                : preg_replace(
                                    '/^JshoppingController/',
                                    '',
                                    get_class($this)
                                )
                        )
                    )
                );
            }
            return $res;
        }

        protected function _item($id) {
            return call_user_func_array(
                [
                    $this->getModel(),
                    'item'
                ],
                func_get_args()
            );
        }

        protected function _items(array $ids): array {
            $this->getModel('adapter')->checkArrayArg(
                $ids,
                (string) reset(
                    (new ReflectionMethod($this, 'item'))->getParameters()
                )->getType(),
                'ids'
            );
            $res = [];
            foreach ($ids as $id) {
                $res[$id] = $this->item($id);
            }
            ksort($res);
            return $res;
        }

        protected function setNote(string $note): bool {
            $this->note = $note;
            return true;
        }

        protected function setCode(int $code): bool {
            $this->code = $code;
            return true;
        }

        protected function setDefaultResult($default_result): bool {
            return $this->default_result = $default_result;
            return true;
        }

        public function getCode(): int {
            return $this->code;
        }

        public function getNote(): string {
            return $this->note;
        }

        public function getDefaultResult() {
            return $this->default_result;
        }

    }
