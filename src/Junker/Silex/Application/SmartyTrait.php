<?php

namespace Junker\Silex\Application;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait SmartyTrait
{
	public function render($template, array $template_data = [], Response $response = null)
	{
		$smarty = $this['smarty'];

		$smarty->assign($template_data);

		if ($response instanceof StreamedResponse) 
		{
			$response->setCallback(function () use ($smarty, $template) 
			{
				$smarty->display($template);
			});
		}
		else 
		{
			if ($response === null)
			    $response = new Response();

			$response->setContent($smarty->fetch($template));
		}

		return $response;
	}
}