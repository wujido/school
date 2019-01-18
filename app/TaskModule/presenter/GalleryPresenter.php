<?php
/**
 * @author Václav Hrouda <vahrouda@gmail.com>.
 * Date: 13.1.19
 */

namespace App\Presenters;



use App\Model\GalleryManager;
use Czubehead\BootstrapForms\BootstrapForm;
use Nette\IOException;
use Nette\Utils\FileSystem;


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

	protected function createComponentCreateSectionForm()
	{
		$form = new BootstrapForm();
		$form->addText('name', 'Jméno sekce:');
		$form->addSubmit('save', 'Přidat');
		$form->onSuccess[] = [$this, 'createSectionForm'];

		return $form;
	}

	public function createSectionForm(BootstrapForm $form, stdClass $values)
	{
		try {

			FileSystem::createDir($this->getHttpRequest()->getUrl()->getBasePath() . '/Gallery-source/' . $values['name']);

		} catch (IOException $e) {
			$this->flashMessage('Něco se pokazilo');
		}

		$this->redirect('Gallery:');
	}
}


