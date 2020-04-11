<?php
    /*
    * @version      1.0.2 30.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $sections_and_tasks = [
        'addon' => [
            'tasks' => [
                'ids' => [
                    'descr'  => 'Returns aliases of all addons',
                    'type'   => 'array'
                ],
                'item' => [
                    'descr'  => 'Returns the information about an addon',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'string',
                            'descr'   => 'Addon\'s alias'
                        ]
                    ]
                ],
                'items' => [
                    'descr'  => 'Returns the information about addons',
                    'type'   => 'array',
                    'params' => [
                        'ids' => [
                            'type'    => 'string array',
                            'descr'   => 'Addons\' aliases'
                        ]
                    ]
                ]
            ]
        ],
        'cart' => [
            'tasks' => [
                'add' => [
                    'descr'  => 'Adds a product into the cart',
                    'type'   => 'bool',
                    'params' => [
                        'product_id' => [
                            'type'    => 'int',
                            'descr'   => 'Product\'s identifier'
                        ],
                        'quantity' => [
                            'type'    => 'int',
                            'descr'   => 'Product\'s quantity'
                        ],
                        'attributes' => [
                            'type'    => 'array',
                            'descr'   => 'An array of attributes in format<table><tr><td><span class="datatype">int</span> <span class="variable">attribut_id</span><td><span class="datatype">string</span> <span class="variable">attribut_value</span></td></tr></table>'
                        ],
                        'freeattributes' => [
                            'type'    => 'array',
                            'descr'   => 'An array of free attributes in format<table><tr><td><span class="datatype">int</span> <span class="variable">free_attribut_id</span></td><td><span class="datatype">string</span> <span class="variable">free_attribut_value</span></td></tr></table>'
                        ],
                        'additional_fields' => [
                            'type'    => 'array',
                            'descr'   => 'An array of additional fields in format<table><tr><td><span class="datatype">string</span> <span class="variable">field_key</span></td><td><span class="datatype">string</span> <span class="variable">field_value</span></td></tr></table>'
                        ]
                    ]
                ],
                'clear' => [
                    'descr'  => 'Clears all the data of the cart',
                    'type'   => 'bool'
                ],
                'delete' => [
                    'descr'  => 'Deletes a product from the cart',
                    'type'   => 'bool',
                    'params' => [
                        'index' => [
                            'type'    => 'int',
                            'descr'   => 'Product\'s index number in the cart'
                        ]
                    ]
                ],
                'discount' => [
                    'descr'  => 'Applies a discount to the cart',
                    'type'   => 'bool',
                    'params' => [
                        'code' => [
                            'type'    => 'string',
                            'descr'   => 'Discount code'
                        ]
                    ]
                ],
                'info' => [
                    'descr'  => 'Returns the information about the cart',
                    'type'   => 'array',
                ],
                'toCart' => [
                    'descr'  => 'Sends a product from the wishlist to the cart',
                    'type'   => 'bool',
                    'params' => [
                        'index' => [
                            'type'    => 'int',
                            'descr'   => 'Product\'s index number in the wishlist'
                        ]
                    ]
                ],
                'update' => [
                    'descr'  => 'Updates products\' quantities in the cart',
                    'type'   => 'bool',
                    'params' => [
                        'quantities' => [
                            'type'    => 'int array',
                            'descr'   => 'An array of new quantities in format<table><tr><td><span class="datatype">int</span> <span class="variable">product_index</span></td><td><span class="datatype">int</span> <span class="variable">product_quantity</span></td></tr></table>'
                        ]
                    ]
                ]
            ]
        ],
        'category' => [
            'tasks' => [
                'ids' => [
                    'descr'  => 'Returns identifiers of all categories',
                    'type'   => 'array'
                ],
                'item' => [
                    'descr'  => 'Returns the information about a category',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'Category identifier. Use <span class="variable">search</span> task of <span class="variable">product</span> section to get category products'
                        ]
                    ]
                ],
                'items' => [
                    'descr'  => 'Returns the information about categories',
                    'type'   => 'array',
                    'params' => [
                        'ids' => [
                            'type'    => 'int array',
                            'descr'   => 'Categories\' identifiers'
                        ]
                    ]
                ],
                'tree' => [
                    'descr'  => 'Returns the categories tree',
                    'type'   => 'array'
                ]
            ]
        ],
        'checkout' => [
            'tasks' => [
                'step2' => [
                    'descr'  => 'Returns the information, needed for making step #2. Start from this step to make an order. Then call saving task. Repeat this procedure for all steps until the last. Note that some steps can be disabled, so before making the next step always check its number by returned parameter <span class="variable">next_step</span> or using tasks <span class="variable">stepNumber</span> or <span class="variable">steps</span>',
                    'type'   => 'array'
                ],
                'step2save' => [
                    'descr'  => 'Makes step #2',
                    'type'   => 'bool',
                    'params' => [
                        'input' => [
                            'type'    => 'array',
                            'descr'   => 'User\'s input in format<table><tr><td><span class="datatype">string</span> <span class="variable">field_key</span></td><td><span class="datatype">string</span> <span class="variable">field_value</span></td></tr></table>'
                        ]
                    ]
                ],
                'step3' => [
                    'descr'  => 'Returns the information, needed for making step #3',
                    'type'   => 'array'
                ],
                'step3save' => [
                    'descr'  => 'Makes step #3',
                    'type'   => 'bool',
                    'params' => [
                        'payment_id' => [
                            'type'    => 'int',
                            'descr'   => 'Payment\'s identifier'
                        ],
                        'extra_params' => [
                            'type'    => 'array',
                            'descr'   => 'Extra parameters',
                            'default' => '[]'
                        ]
                    ]
                ],
                'step4' => [
                    'descr'  => 'Returns the information, needed for making step #4',
                    'type'   => 'array'
                ],
                'step4save' => [
                    'descr'  => 'Makes step #4',
                    'type'   => 'bool',
                    'params' => [
                        'shipping_id' => [
                            'type'    => 'int',
                            'descr'   => 'Shipping\'s identifier'
                        ],
                        'extra_params' => [
                            'type'    => 'array',
                            'descr'   => 'Extra parameters',
                            'default' => '[]'
                        ]
                    ]
                ],
                'step5' => [
                    'descr'  => 'Returns the information, needed for making step #5',
                    'type'   => 'array'
                ],
                'step5save' => [
                    'descr'  => 'Makes step #5. This task returns filled parameter <span class="variable">payment_form</span> if payment is needed and empty otherwise. To make the payment display the contents of this parameter as HTML of version 5 on a needed page of the application. In most cases an application user will be automatically redirected to a web page of a payment system, chosen in <span class="variable">step3save</span> task. In some cases (\'Pay Pal PLUS\' for example) the user need to choose some parameters before the redirect. At that web page the user need to make a payment in a standard way. After the payment or cancellation the user will be redirected back to the application by the link passed in <span class="variable">step5save</span> task as <span class="variable">payment_back_link</span> parameter. GET parameter <span class="variable">act</span> also will be added to the back link. In most cases it will be equals to <span class="variable">return</span> if the payment was suuccessful, <span class="variable">cancel</span> if the payment was cancelled and <span class="variable">error</span> if some error has occurred. Later on, if the payment was successful, the payment system will send the confirmation request to the shop by its own',
                    'type'   => 'array',
                    'params' => [
                        'confirmation' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s confirmation'
                        ],
                        'payment_back_link' => [
                            'type'    => 'string',
                            'descr'   => 'URL, where a user will be redirected after the completion or cancellation of the payment'
                        ],
                        'extra_params' => [
                            'type'    => 'array',
                            'descr'   => 'Extra parameters',
                            'default' => '[]'
                        ]
                    ]
                ],
                'stepNumber' => [
                    'descr'  => 'Get the number of the current step',
                    'type'   => 'int'
                ],
                'steps' => [
                    'descr'  => 'Returns numbers of all steps',
                    'type'   => 'array'
                ],
            ]
        ],
        'connection' => [
            'tasks' => [
                'close' => [
                    'descr'  => 'Closes the current connection with the API. Call it always after end of work with the API',
                    'type'   => 'bool'
                ],
                'info' => [
                    'descr'  => 'Returns the information about the current connection with the API',
                    'type'   => 'array'
                ],
                'open' => [
                    'descr'  => 'Opens a new connection with the API and returns the token of it',
                    'type'   => 'string'
                ],
                'user' => [
                    'descr'  => 'Returns the information about the currently connected API user',
                    'type'   => 'array'
                ]
            ]
        ],
        'content' => [
            'tasks' => [
                'cartReturnPolicy' => [
                    'descr'  => 'Returns the information about cart\'s return policy',
                    'type'   => 'array'
                ],
                'ids' => [
                    'descr'  => 'Returns aliases of all shop content pages',
                    'type'   => 'array'
                ],
                'item' => [
                    'descr'  => 'Returns the information about a shop content page',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'string',
                            'descr'   => 'Shop content page alias'
                        ]
                    ]
                ],
                'items' => [
                    'descr'  => 'Returns the information about shop content pages',
                    'type'   => 'array',
                    'params' => [
                        'ids' => [
                            'type'    => 'string array',
                            'descr'   => 'Shop content pages\' aliases'
                        ]
                    ]
                ],
                'orderReturnPolicy' => [
                    'descr'  => 'Returns the information about order\'s return policy',
                    'type'   => 'array',
                    'params' => [
                        'order_id' => [
                            'type'    => 'int',
                            'descr'   => 'Order identifier'
                        ]
                    ]
                ]
            ]
        ],
        'order' => [
            'tasks' => [
                'ids' => [
                    'descr'  => 'Returns identifiers of all orders',
                    'type'   => 'array'
                ],
                'item' => [
                    'descr'  => 'Returns the information about an order',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'Order\'s identifier'
                        ]
                    ]
                ],
                'items' => [
                    'descr'  => 'Returns the information about orders',
                    'type'   => 'array',
                    'params' => [
                        'ids' => [
                            'type'    => 'int array',
                            'descr'   => 'Orders\' identifiers'
                        ]
                    ]
                ],
                'states' => [
                    'descr'  => 'Returns the information about orders\' states',
                    'type'   => 'array'
                ]
            ]
        ],
        'product' => [
            'tasks' => [
                'group' => [
                    'descr'  => 'Returns the information about products of a specified group',
                    'type'   => 'array',
                    'params' => [
                        'group' => [
                            'type'    => 'string',
                            'descr'   => 'Products\' group:<table><tr><td class="variable">bestseller</td><td>The best-selling products</td></tr><tr><td class="variable">last</td><td>The last-added products</td></tr><tr><td class="variable">random</td><td>A random set of products</td></tr><tr><td class="variable">tophits</td><td>The most-viewed products</td></tr><tr><td class="variable">toprating</td><td>The most rated products</td></tr></table>'
                        ]
                    ]
                ],
                'ids' => [
                    'descr'  => 'Returns identifiers of all products',
                    'type'   => 'array'
                ],
                'item' => [
                    'descr'  => 'Returns the information about a product',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'Product\'s identifier'
                        ],
                        'attributes' => [
                            'type'    => 'array',
                            'descr'   => 'An array of attributes in format<table><tr><td><span class="datatype">int</span> <span class="variable">attribut_id</span></td><td><span class="datatype">string</span> <span class="variable">attribut_value</span></td></tr></table>',
                            'default' => '[]'
                        ]
                    ]
                ],
                'items' => [
                    'descr'  => 'Returns the information about products',
                    'type'   => 'array',
                    'params' => [
                        'ids' => [
                            'type'    => 'int array',
                            'descr'   => 'Products\' identifiers'
                        ]
                    ]
                ],
                'search' => [
                    'descr'  => 'Returns the list of found products',
                    'type'   => 'array',
                    'params' => [
                        'search' => [
                            'type'    => 'string',
                            'descr'   => 'A search query',
                            'default' => '\'\''
                        ],
                        'search_type' => [
                            'type'    => 'string',
                            'descr'   => 'Search by:<table><tr><td class="variable">all</td><td>All parts of the search query</td></tr><tr><td class="variable">any</td><td>Any part of the query</td></tr><tr><td class="variable">exact</td><td>Exact match with the query</td></tr></table><p>Has a matter only when a search query is not empty</p>',
                            'default' => 'any'
                        ],
                        'categories' => [
                            'type'    => 'int array',
                            'descr'   => 'Categories\' identifiers',
                            'default' => '[]'
                        ],
                        'include_subcat' => [
                            'type'    => 'bool',
                            'descr'   => 'Whether to search in subcategories',
                            'default' => 'true'
                        ],
                        'manufacturers' => [
                            'type'    => 'int array',
                            'descr'   => 'Manufacturers\' identifiers',
                            'default' => '[]'
                        ],
                        'vendors' => [
                            'type'    => 'int array',
                            'descr'   => 'Vendors\' identifiers',
                            'default' => '[]'
                        ],
                        'labels' => [
                            'type'    => 'int array',
                            'descr'   => 'Labels\' identifiers',
                            'default' => '[]'
                        ],
                        'price_from' => [
                            'type'    => 'float string',
                            'descr'   => 'Minimum product price',
                            'default' => 0
                        ],
                        'price_to' => [
                            'type'    => 'float string',
                            'descr'   => 'Maximum product price',
                            'default' => 0
                        ],
                        'date_from' => [
                            'type'    => 'string',
                            'descr'   => 'Minimum product creation date in format <span class="variable">YYYY-MM-DD HH:MM:SS</span>',
                            'default' => '\'\''
                        ],
                        'date_to' => [
                            'type'    => 'string',
                            'descr'   => 'Maximum product creation date in format <span class="variable">YYYY-MM-DD HH:MM:SS</span>',
                            'default' => '\'\''
                        ],
                        'extra_fields' => [
                            'type'    => 'array',
                            'descr'   => 'Products\' extra fields in format<table><tr><td><span class="datatype">int</span> <span class="variable">extra_field_id</span></td><td><span class="datatype">string</span> <span class="variable">extra_field_value</span></td></tr></table>or<table><tr><td><span class="datatype">int</span> <span class="variable">extra_field_id</span></td><td><span class="datatype">int array</span> <span class="variable">extra_field_values_ids</span></td></tr></table><p>The support of extra fields can be switched off by the site administrator</p>',
                            'default' => '[]'
                        ],
                        'order' => [
                            'type'    => 'int',
                            'descr'   => 'Identifier of a property by which to order products. One of keys of <span class="variable">sorting_products_field_s_select</span> parameter of the shop configuration. The default value is stored in the shop configuration under <span class="variable">product_sorting</span> key and can be changed by the site administrator',
                            'default' => 1
                        ],
                        'orderby' => [
                            'type'    => 'int',
                            'descr'   => 'Products order direction:<table><tr><td class="variable">0<td>Ascending</td></tr><tr><td class="variable">1<td>Descending</td></tr></table><p>The default value is stored in the shop configuration under <span class="variable">product_sorting_direction</span> key and can be changed by the site administrator</p>',
                            'default' => 0
                        ],
                        'limit' => [
                            'type'    => 'int',
                            'descr'   => 'Number of products to return. The default value is stored in the shop configuration under <span class="variable">count_products_to_page</span> key and can be changed by the site administrator',
                            'default' => 12
                        ],
                        'limitstart' => [
                            'type'    => 'int',
                            'descr'   => 'Start position of products. Used for pagination',
                            'default' => 0
                        ]
                    ]
                ],
                'searchInfo' => [
                    'descr'  => 'Returns the information, needed for the products searching',
                    'type'   => 'array'
                ]
            ]
        ],
        'shop' => [
            'tasks' => [
                'config' => [
                    'descr'  => 'Returns the shop configuration',
                    'type'   => 'array'
                ]
            ]
        ],
        'user' => [
            'tasks' => [
                'activate' => [
                    'descr'  => 'Activates a new user account. Returns the information about the just activated user',
                    'type'   => 'array',
                    'params' => [
                        'token' => [
                            'type'    => 'string',
                            'descr'   => 'Activation token'
                        ]
                    ]
                ],
                'cancelOrder' => [
                    'descr'  => 'Cancels an order',
                    'type'   => 'bool',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s identifier'
                        ],
                        'order_id' => [
                            'type'    => 'int',
                            'descr'   => 'Order\'s identifier'
                        ]
                    ]
                ],
                'changePassword' => [
                    'descr'  => 'Changes the password of a user',
                    'type'   => 'bool',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s identifier'
                        ],
                        'old_password' => [
                            'type'    => 'string',
                            'descr'   => 'User\'s current password'
                        ],
                        'new_password' => [
                            'type'    => 'string',
                            'descr'   => 'User\'s new password'
                        ]
                    ]
                ],
                'create' => [
                    'descr'  => 'Registers a new user. Returns the information about the just registered user',
                    'type'   => 'array',
                    'params' => [
                        'input' => [
                            'type'    => 'array',
                            'descr'   => 'Registration data in format<table><tr><td><span class="datatype">string</span> <span class="variable">field_key</span></td><td><span class="datatype">string</span> <span class="variable">field_value</span></td></tr></table>'
                        ]
                    ]
                ],
                'createInfo' => [
                    'descr'  => 'Returns the information, needed for a new user registration',
                    'type'   => 'array'
                ],
                'edit' => [
                    'descr'  => 'Edits the information about a user',
                    'type'   => 'bool',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s identifier'
                        ],
                        'input' => [
                            'type'    => 'array',
                            'descr'   => 'New user\'s data in format<table><tr><td><span class="datatype">string</span> <span class="variable">field_key</span></td><td><span class="datatype">string</span> <span class="variable">field_value</span></td></tr></table>'
                        ]
                    ]
                ],
                'editInfo' => [
                    'descr'  => 'Returns the information for editing of user\'s data',
                    'type'   => 'array'
                ],
                'groups' => [
                    'descr'  => 'Returns the information about user groups',
                    'type'   => 'array'
                ],
                'ids' => [
                    'descr'  => 'Returns identifiers of all users',
                    'type'   => 'array'
                ],
                'item' => [
                    'descr'  => 'Returns the information about a user',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s identifier. The current user\'s identifier will be used if <span class="variable">id</span> is <span class="variable">0</span>',
                            'default' => 0
                        ]
                    ]
                ],
                'items' => [
                    'descr'  => 'Returns the information about users',
                    'type'   => 'array',
                    'params' => [
                        'ids' => [
                            'type'    => 'int array',
                            'descr'   => 'Users\' identifiers'
                        ]
                    ]
                ],
                'login' => [
                    'descr'  => 'Logs a user in',
                    'type'   => 'bool',
                    'params' => [
                        'username' => [
                            'type'    => 'string',
                            'descr'   => 'User\'s name'
                        ],
                        'password' => [
                            'type'    => 'string',
                            'descr'   => 'User\'s password'
                        ]
                    ]
                ],
                'logout' => [
                    'descr'  => 'Logs the current user out',
                    'type'   => 'bool'
                ],
                'order' => [
                    'descr'  => 'Returns the information about a user\'s order',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s identifier'
                        ],
                        'order_id' => [
                            'type'    => 'int',
                            'descr'   => 'Order\'s identifier'
                        ]
                    ]
                ],
                'orders' => [
                    'descr'  => 'Returns the information about user\'s orders',
                    'type'   => 'array',
                    'params' => [
                        'id' => [
                            'type'    => 'int',
                            'descr'   => 'User\'s identifier'
                        ],
                        'orders_ids' => [
                            'type'    => 'int array',
                            'descr'   => 'Orders\' identifiers'
                        ]
                    ]
                ]
            ]
        ],
        'wishlist' => []
    ];
    $sections_and_tasks['wishlist'] = $sections_and_tasks['cart'];
    unset(
        $sections_and_tasks['cart']['tasks']['toCart'],
        $sections_and_tasks['wishlist']['tasks']['discount']
    );
    foreach ($sections_and_tasks['wishlist']['tasks'] as $task_name => &$task) {
        if (in_array($task_name, ['toCart'])) {
            continue;
        }
        $task['descr'] = str_replace('cart', 'wishlist', $task['descr']);
        if (isset($task['params'])) {
            foreach ($task['params'] as &$param) {
                $param['descr'] = str_replace('cart', 'wishlist', $param['descr']);
            }
        }
    }
