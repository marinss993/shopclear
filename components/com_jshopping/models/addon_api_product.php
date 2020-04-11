<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_product extends JshoppingModelAddon_api {

        public function getProductList(
            string $search          = '',
            string $search_type     = '',
            array  $categorys       = [],
            int    $include_subcat  = 1,
            array  $manufacturers   = [],
            array  $vendors         = [],
            array  $labels          = [],
            float  $price_from      = 0,
            float  $price_to        = 0,
            string $date_from       = '',
            string $date_to         = '',
            array  $extra_fields    = [],
            int    $order           = 0,
            int    $orderby         = 0,
            int    $limit           = 0,
            int    $limitstart      = 0
        ): jshopProductList {
            $args    = get_defined_vars();
            $app     = JFactory::getApplication();
            $jinp    = $app->input;
            $model   = AddonApi::getInst()->getModel('productssearch');
            $context = $model->getContext();
            foreach ($args as $name => $value) {
                switch ($name) {
                    case 'order':
                    case 'orderby':
                    case 'limit':
                        $app->setUserState($context . $name, $value);
                        break;
                    default:
                        $jinp->set($name, $value);
                }
            }
            $productlist = JSFactory::getModel('productList', 'jshop');
            $productlist->setModel($model);
            $productlist->load();
            return $productlist;
        }

        /**
         * @throws AddonApiException
         */
        public function group(string $group): array {
            $model = JSFactory::getModel('products' . $group, 'jshop');
            if ($group == 'label' || $model === false) {
                throw new AddonApiException_product(2, ' \'' . $group . '\'');
            }
            $productlist = JSFactory::getModel('productList', 'jshop');
            $productlist->setMultiPageList(0);
            $productlist->setModel($model);
            $productlist->load();
            $productlist->configDisableSortAndFilters();
            $view = $this->getView('products');
            $res  = [
                'allow_review'          => (bool)  $productlist->getAllowReview(),
                'count_product_to_row'  => (int)   $productlist->getCountProductsToRow(),
                'display_list_products' => (bool)  $productlist->getDisplayListProducts(),
                'display_pagination'    => false,
                'products'              => (array) $productlist->getProducts()
            ];
            $this->triggerView('onBeforeDisplayProductListView', $res, [&$view, &$productlist]);
            return $res;
        }

        public function ids(): array {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . $db->qn('product_id') . '
                FROM '   . $db->qn('#__jshopping_products')
            );
            $res = array_map('intval', (array) $db->loadColumn());
            sort($res);
            return $res;
        }

        /**
         * @throws AddonApiException
         */
        public function item(int $id, array $attributes = []): array {
            $category_id     = 0;
            $user            = JFactory::getUser();
            $jshopConfig     = JSFactory::getConfig();
            $model           = JSFactory::getModel('productShop', 'jshop');
            $modelreviewlist = JSFactory::getModel('productReviewList', 'jshop');
            $back_value      = $model->getBackValue($id, $attributes);
            $dispatcher      = JDispatcher::getInstance();
            $dispatcher->trigger('onBeforeLoadProduct', [&$id, &$category_id, &$back_value]);
            $dispatcher->trigger('onBeforeLoadProductList', []);
            $product         = JSFactory::getTable('product');
            $product->load($id);
            if (!$product->product_id) {
				throw new AddonApiException_product(1, ' ' . $id);
            }
            $model->setProduct($product);
            $model->prepareView($back_value);
            $model->clearBackValue();
            $listcategory = $model->getCategories(1);
            $category_id  = reset($listcategory);
            $category     = JSFactory::getTable('category');
            $category->load($category_id);
            $modelreviewlist->setModel($product);
            $modelreviewlist->load();
            $product->hit();
            $product_images    = $product->getImages();
            $product_videos    = $product->getVideos();
            $product_demofiles = $product->getDemoFiles();
            $view              = $this->getView('product');
            $dispatcher->trigger('onBeforeDisplayProductList', [&$product->product_related]);
            $dispatcher->trigger('onBeforeDisplayProduct', [&$product, &$view, &$product_images, &$product_videos, &$product_demofiles]);
            $res = [
                'all_attr_values'          => (array)  $model->getAllAttrValues(),
                'allow_review'             => (bool)   $model->getAllowReview(),
                'attributes'               => (array)  $model->getAttributes(),
                'available'                => (string) $model->getTextAvailable(),
                'back_value'               => (array)  $back_value,
                'default_count_product'    => (int)    $model->getDefaultCountProduct($back_value),
                'demofiles'                => (array)  $product_demofiles,
                'display_pagination'       => (bool)   $modelreviewlist->getPagination()->getPagesLinks(),
                'enable_wishlist'          => (bool)   $jshopConfig->enable_wishlist,
                'hide_buy'                 => (bool)   $model->getHideBuy(),
                'image_path'               => (string) $jshopConfig->live_path . 'images',
                'image_product_path'       => (string) $jshopConfig->image_product_live_path,
                'images'                   => (array)  $product_images,
                'noimage'                  => (string) $jshopConfig->noimage,
                'parts_count'              => (int)    $jshopConfig->rating_starparts,
                'product'                  =>          $product,
                'related_prod'             => (array)  $product->product_related,
                'reviews'                  => (array)  $modelreviewlist->getList(),
                'select_review'            => (array)  range(0, $jshopConfig->max_mark),
                'stars_count'              => (int)    floor($jshopConfig->max_mark / $jshopConfig->rating_starparts),
                'text_review'              => (string) $model->getTextReview(),
                'video_image_preview_path' => (string) $jshopConfig->video_product_live_path,
                'video_product_path'       => (string) $jshopConfig->video_product_live_path,
                'videos'                   => (array)  $product_videos
            ];
            $this->triggerView('onBeforeDisplayProductView', $res, [&$view]);
            $dispatcher->trigger('onAfterDisplayProduct', [&$product]);
            return $res;
        }

        public function search(
            string $search          = '',
            string $search_type     = '',
            array  $categories      = [],
            int    $include_subcat  = 1,
            array  $manufacturers   = [],
            array  $vendors         = [],
            array  $labels          = [],
            float  $price_from      = 0,
            float  $price_to        = 0,
            string $date_from       = '',
            string $date_to         = '',
            array  $extra_fields    = [],
            int    $order           = 0,
            int    $orderby         = 0,
            int    $limit           = 0,
            int    $limitstart      = 0
        ): array {
            $productlist = $this->getProductList(...func_get_args());
            $view        = $this->getView('search');
            $res         = [
                'allow_review'             => (bool)   $productlist->getAllowReview(),
                'count_product_to_row'     => (int)    $productlist->getCountProductsToRow(),
                'display_pagination'       => (bool)   $productlist->getPagenav() != '',
                'filters'                  => (array)  $productlist->getFilters(),
                'filter_show'              => false,
                'filter_show_category'     => false,
                'filter_show_manufacturer' => false,
                'orderby'                  => (string) $productlist->getOrderBy(),
                'pagination'               =>          $productlist->getPagination(),
                'path_image_sorting_dir'   => (string) JSFactory::getConfig()->live_path . 'images/' . $productlist->getImageSortDir(),
                'product_count'            => (array)  JshopHelpersSelectOptions::getProductsCount(),
                'products'                 => (array)  $productlist->getProducts(),
                'sorting'                  => (array)  JshopHelpersSelectOptions::getProductsOrdering(),
                'total'                    => (int)    $productlist->getTotal()
            ];
            $this->triggerView('onBeforeDisplayProductListView', $res, [&$view, &$productlist]);
            return $res;
        }

        public function searchInfo() {
            return [
                'categories'    => (array) JshopHelpersSelectOptions::getCategories(0),
                'product_count' => (array) JshopHelpersSelectOptions::getProductsCount(),
                'extra_fields'  => array_intersect_key(
                    (array) JSFactory::getAllProductExtraField(),
                    array_flip((array) JSFactory::getDisplayFilterExtraFieldForCategory(0))
                ),
                'labels'        => (array) JshopHelpersSelectOptions::getLabels(0),
                'manufacturers' => (array) JshopHelpersSelectOptions::getManufacturers(0),
                'order'         => (array) JshopHelpersSelectOptions::getProductsOrdering(),
                'vendors'       => (array) JshopHelpersSelectOptions::getVendors(0)
            ];
        }

    }
