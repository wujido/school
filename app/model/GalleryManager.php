<?php
/**
 * @author Václav Hrouda <vahrouda@gmail.com>.
 * Date: 13.1.19
 */

namespace App\Model;


use Nette\Utils\Finder;

class GalleryManager extends BaseManager
{

	const GALLERY_DEFAULT = 'Gallery-source';

	private $masks = ['*.jpg', '*.jpeg', '*.png', '*.gif'];

	public function getImages()
	{
		return Finder::findFiles($this->masks)->in(self::GALLERY_DEFAULT . '/default');
	}


	public function getImagesByName($name)
	{
		return Finder::findFiles($this->masks)->in(self::GALLERY_DEFAULT . '/' . $this->fileToFolderName($name));
	}


	private function fileToFolderName($name)
	{
		return trim(str_replace(' ', '', ucwords(str_replace('-', ' ', str_replace('_', ' ', pathinfo($name, PATHINFO_FILENAME))))));
	}
}