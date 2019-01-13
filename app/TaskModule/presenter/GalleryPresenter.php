<?php
/**
 * @author Václav Hrouda <vahrouda@gmail.com>.
 * Date: 13.1.19
 */

namespace App\Presenters;



use App\Model\GalleryManager;

class GalleryPresenter extends BasePresenter
{

	/**
	 * @var GalleryManager
	 */
	private $galleryManager;

	public function __construct(GalleryManager $galleryManager)
	{
		$this->galleryManager = $galleryManager;
	}

	public function renderDefault()
	{
		$this->template->images = $this->galleryManager->getImages();
	}

	public function renderDetail($name)
	{
		$this->template->images =  $this->galleryManager->getImagesByName($name);
	}
}


