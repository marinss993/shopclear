<?php
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

require_once JPATH_THEMES . '/' . $this->template . '/helper.php';
tplFooHelper::loadCss();
tplFooHelper::loadJs();
tplFooHelper::setMetadata();

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,700i,800,800i,900" rel="stylesheet">
    <jdoc:include type="head" />
</head>

<body class="<?php echo tplFooHelper::setBodySuffix(); ?>">
    <header>


        <div class="container header-container">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 logo-block-header">
                    <h2><a style="color:black;font-weight: 800;" href="<?php print JUri::root() ?>">
                    DemoShop Joomla
                    </a></h2>
                    
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="row header-row-2">
                        <div class="wrap-currency">
                            <?php if ($this->countModules('currency')) { ?><?php 
                                                                        } ?>
                            <div class="currency">
                                <jdoc:include type="modules" name="currency" style="xhtml" />
                            </div>
                            <div style="display:inline-block;">
                                <jdoc:include type="modules" name="language-change" style="none" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mobile-off">
                    <div class="containers">
                        <div class="row header-row-3">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 pr-0">
                                <div class="flex-row">
                                    <div class="flex-column1">
                                        <div>
                                            <div class="category-header-title2 category-header-title">
                                                <div class="menu-header-joom">
                                                    <jdoc:include type="modules" name="shop" style="xhtmlshop" />
                                                </div>
                                                <div class="categories-header">
                                                    <jdoc:include type="modules" name="categories" style="none" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-column2">
                                        <jdoc:include type="modules" name="login-menu" style="xhtml" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container row mobile-onn"><a href="#" onclick="mouseMenu(event)">
                <button class="button-menu">MENU</button></a>
        </div>
        <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="categoty-mobile" id="categoty-mobile">
                <div class="row menu-header-joom-mobile">
                    <div class="col">SHOP</div>
                    <div class="col">
                        <div class="border-mobile">
                            <div class="vl-click" id="vl-click" onclick="mouseMenuTwo(event)"></div>
                        </div>
                    </div>
                </div>
                <div class="categories-header-mobile" id="id-mobile-menu">
                    <jdoc:include type="modules" name="categories" style="none" />
                </div>
                <div class="menu-header-joom-mobile">
                    <jdoc:include type="modules" name="login-menu" />
                </div>
            </div>
    </header>
    <div class="container content-home">
        <div class="row">
            <!--Block Content-->
            <div class="col-lg-8 column-8">
                <div>
                    <?php if ($this->countModules('home-image')) { ?>
                    <div>
                        <jdoc:include type="modules" name="home-image" style="xhtml" />
                    </div>
                    <?php } ?>
                </div>
                <jdoc:include type="message" />
                <jdoc:include type="component" />                
                <?php if ($this->countModules('label-products')) { ?>
                <div class="widget-2">
                    <div class="label-products">
                        <jdoc:include type="modules" name="label-products" style="xhtml" />
                    </div>
                </div>
                <?php 
            } ?>
                <?php if ($this->countModules('bestseller-products')) { ?>
                <div class="widget-2">
                    <div class="bestseller-products">
                        <jdoc:include type="modules" name="bestseller-products" style="xhtml" />
                    </div>
                </div>
                <?php 
            } ?>
                <?php if ($this->countModules('latest-products')) { ?>
                <div class="widget-2">
                    <div class="latest-products">
                        <jdoc:include type="modules" name="latest-products" style="xhtml" />
                    </div>
                </div>
                <?php 
            } ?>
            </div>
            <!--Block menu-->
            <div class="col-lg-4 column-4">
                <div class="widget">
                    <?php if ($this->countModules('module-search')) { ?>
                    <div class="module-search">
                        <jdoc:include type="modules" name="module-search" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('basket')) { ?>
                    <div class="basket">
                        <jdoc:include type="modules" name="basket" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('wishlist')) { ?>
                    <div class="wishlist">
                        <jdoc:include type="modules" name="wishlist" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('manufactures')) { ?>
                    <div class="categories">
                        <jdoc:include type="modules" name="categories-two" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('manufactures')) { ?>
                    <div class="manufactures">
                        <jdoc:include type="modules" name="manufactures" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('top-hits-products')) { ?>
                    <div class="top-hits-products">
                        <jdoc:include type="modules" name="top-hits-products" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('rating-products')) { ?>
                    <div class="rating-products">
                        <jdoc:include type="modules" name="rating-products" style="xhtml" />
                    </div>
                    <?php 
                } ?>
                </div>
                <div class="widget">
                    <?php if ($this->countModules('recent-comments')) { ?>
                    <div class="recent-comments">
                        <jdoc:include type="modules" name="recent-comments" style="xhtml" />
                    </div>
                    <div class="container-button-top" onclick="buttonTop()"><button class="button-top"></button></div>

                    <?php 
                } ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <footer>
        <div class="container">
            <jdoc:include type="modules" name="footer" style="none" />
            <p>
                &copy; <?php echo date('Y'); ?> <?php echo tplFooHelper::getSitename(); ?>
            </p>
            <jdoc:include type="modules" name="debug" style="none" />
        </div>
    </footer>
</body>

</html> 