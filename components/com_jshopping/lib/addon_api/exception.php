<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class AddonApiException extends Exception {

        protected
            $status = '';

        public function __construct(
            int       $code,
            string    $message = '',
            Throwable $previous = null
        ) {
            $caller       = get_class($this);
            $this->status = (
                strpos($caller, __CLASS__) === 0
                    ? strtolower(
                        preg_replace('/^' . __CLASS__ . '_/', '', $caller)
                    )
                    : JStringNormalise::toKey(
                        JStringNormalise::fromCamelCase(
                            preg_replace('/' . __CLASS__ . '$/', '', $caller)
                        )
                    )
            ) . '_error';
            parent::__construct($message, $code, $previous);
        }

        public function getStatus(): string {
            return $this->status;
        }

	}
