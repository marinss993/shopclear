<?php
    /*
    * @version      1.0.3 01.11.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $version_history = [
        [
            'date'    => '01.11.2018',
            'version' => '1.0.3',
            'changes' => [
                'Fixed HTTP authorization errors reporting',
                'Added <span class="variable">Example</span> documentation subsection'
            ]
        ],
        [
            'date'    => '30.10.2018',
            'version' => '1.0.2',
            'changes' => [
                'Standardized the basic features'
            ]
        ],
        [
            'date'    => '18.10.2018',
            'version' => '1.0.1',
            'changes' => [
                'Integrated the support of addons',
                'Added <span class="variable">content</span> section',
            ]
        ],
        [
            'date'    => '22.02.2018',
            'version' => '1.0.0',
            'changes' => [
                'Integrated the support of PHP version 7.1',
                'Integrated the support of PHP version 7.2',
                'Renamed <span class="variable">fromWishlistToCart</span> task to <span class="variable">toCart</span> in <span class="variable">wishlist</span> section'
            ]
        ],
        [
            'date'    => '08.12.2017',
            'version' => '0.2.6',
            'changes' => [
                'Added <span class="variable">update</span> task in <span class="variable">cart</span> section',
                'Added <span class="variable">update</span> task in <span class="variable">wishlist</span> section',
                'Added <span class="variable">group</span> task in <span class="variable">product</span> section',
                'Added <span class="variable">search</span> task in <span class="variable">product</span> section',
                'Added <span class="variable">searchInfo</span> task in <span class="variable">product</span> section',
                'Separated <span class="variable">cart</span> and <span class="variable">wishlist</span> reports and tasks',
                'Changed parameters of <span class="variable">item</span> task in <span class="variable">category</span> section'
            ]
        ],
        [
            'date'    => '01.12.2017',
            'version' => '0.2.5',
            'changes' => [
                'Deleted <span class="variable">user</span> return parameter of <span class="variable">createInfo</span> task of <span class="variable">user</span> section',
                'Deleted <span class="variable">user_id</span> parameter of <span class="variable">changePassword</span> task of <span class="variable">user</span> section',
                'Added <span class="variable">edit</span> task in <span class="variable">user</span> section',
                'Added <span class="variable">editInfo</span> task in <span class="variable">user</span> section',
                'Added <span class="variable">groups</span> task in <span class="variable">user</span> section',
                'Added <span class="variable">order</span> task in <span class="variable">user</span> section',
                'Added <span class="variable">orders</span> task in <span class="variable">user</span> section',
                'Added <span class="variable">ordersAll</span> task in <span class="variable">user</span> section'
            ]
        ]
    ];
