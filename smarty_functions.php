<?php

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

function smarty_function_is_granted($params, &$smarty)
{
	$app = $smarty->getTemplateVars('app');

	$role = $params['role'];

	return $app['security.authorization_checker']->isGranted($role);
}

function smarty_function_path($params, &$smarty)
{
	return path($params, $smarty);
}

function smarty_function_url($params, &$smarty)
{
	return path($params, $smarty, TRUE);
}

function smarty_function_trans($params, &$smarty)
{
	$app = $smarty->getTemplateVars('app');

	$name = $params['_name'];
	unset($params['_name']);
	$domain = isset($params['_domain']) ? $params['_domain'] : NULL;
	unset($params['_domain']);
	$locale = isset($params['_locale']) ? $params['_locale'] : NULL;
	unset($params['_locale']);

	$trans_param = array();
	foreach($params as $param_name => $param_value)
	{
		$trans_param["%$param_name%"] = $param_value;
	}

	return $app['translator']->trans($name, $trans_param, $domain, $locale);
}

function smarty_function_transChoice($params, &$smarty)
{
	$app = $smarty->getTemplateVars('app');

	$name = $params['_name'];
	unset($params['_name']);
	$domain = isset($params['_domain']) ? $params['_domain'] : NULL;
	unset($params['_domain']);
	$locale = isset($params['_locale']) ? $params['_locale'] : NULL;
	unset($params['_locale']);
	$count = isset($params['_count']) ? $params['_count'] : NULL;
	unset($params['_count']);
	$params['%count%'] = $count;


	return $app['translator']->transChoice($name, $count, $params, $domain, $locale);
}

function path($params, &$smarty, $absolute = FALSE)
{
	$app = $smarty->getTemplateVars('app');

	if (isset($params['_name']))
	{
		$name = $params['_name'];
		unset($params['_name']);
	}
	else
	{
		$name = $app['request']->attributes->get('_route');
		$params = array_merge(array_merge($app['request']->attributes->get('_route_params'), $app['request']->query->all(), $params));
	}

	if (isset($params['_params']))
	{
		$params = array_merge($params['_params'], $params);
		unset($params['_params']);
	}

	return $app['url_generator']->generate($name, $params, $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
}