<?php
/**
 * Created by PhpStorm.
 * User: hrouda.vaclav
 * Date: 10.12.2018
 * Time: 15:19
 */

namespace App\Model;


use Nette\Utils\Finder;

class GalleryManager extends BaseManager
{
	public function getDefaultImages($dir)
	{
		return Finder::findFiles('default_*.*')->in($dir);
	}
}