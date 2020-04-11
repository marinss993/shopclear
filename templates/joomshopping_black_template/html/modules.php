<?php

defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg. To render a module mod_test in the submenu style, you would use the following include:
 * <jdoc:include type="module" name="test" style="submenu" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

/*
 * Module chrome for rendering the module in a submenu
 */
function modChrome_no($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo $module->content;
	}
}

function modChrome_well($module, &$params, &$attribs)
{
	$moduleTag     = $params->get('module_tag', 'div');
	$bootstrapSize = (int) $params->get('bootstrap_size', 0);
	$moduleClass   = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';
	$headerTag     = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
	$headerClass   = htmlspecialchars($params->get('header_class', 'page-header'), ENT_COMPAT, 'UTF-8');

	if ($module->content)
	{
		echo '<' . $moduleTag . ' class="well ' . htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') . $moduleClass . '">';

		if ($module->showtitle)
		{
			echo '<' . $headerTag . ' class="' . $headerClass . '">' . $module->title . '</' . $headerTag . '>';
		}

		echo $module->content;
		echo '</' . $moduleTag . '>';
	}
}


/*
 * xhtml (divs and font header tags)
 * With the new advanced parameter it does the same as the html5 chrome
 */
function modChrome_xhtmlshop($module, &$params, &$attribs)
{
	$moduleTag      = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
	$headerTag      = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
	$bootstrapSize  = (int) $params->get('bootstrap_size', 0);
	$moduleClass    = $bootstrapSize !== 0 ? ' span' . $bootstrapSize : '';
	require_once (JPATH_SITE . '/components/com_jshopping/lib/factory.php');
	require_once (JPATH_SITE . '/components/com_jshopping/lib/functions.php');

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class');
	$headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty ($module->content)) : ?>
		<<?php echo $moduleTag; ?> class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') . $moduleClass; ?>">
			<?php if ((bool) $module->showtitle) : ?>
				<<?php echo $headerTag . $headerClass . '><a href="'.SEFLink('index.php?option=com_jshopping', 1).'">' . $module->title . '</a>' ?></<?php echo $headerTag; ?>>
			<?php endif; ?>
			<?php echo $module->content; ?>
		</<?php echo $moduleTag; ?>>
	<?php endif;
}
