<?php
/**
 * Created by PhpStorm.
 * User: hrouda.vaclav
 * Date: 10.12.2018
 * Time: 15:16
 */

namespace App\Presenters;


use App\Model\GalleryManager;
use Nette\Utils\Finder;

class GalleryPresenter extends BasePresenter
{
	/** @var GalleryManager @inject */
	public $galleryManager;

	public function renderDefault()
	{
//		dump($this->galleryManager->getDefaultImages($this->getHttpRequest()->getUrl()->getBasePath() . 'images/gallery'));
		$this->template->defaults = $this->galleryManager->getDefaultImages('images/gallery');

	}
}