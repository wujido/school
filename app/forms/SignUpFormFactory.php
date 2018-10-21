<?php

namespace App\Forms;

use App\Model;
use Czubehead\BootstrapForms\BootstrapForm;
use Czubehead\BootstrapForms\Enums\RenderMode;
use Nette;
use Nette\Application\UI\Form;


final class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 7;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;


	public function __construct(FormFactory $factory, Model\UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->renderMode = RenderMode::SideBySideMode;
		$form->addText('username', 'Zvolte si uživatelské jméno:')
			->setRequired('Prosím zvolte si uživatelské jméno.');

		$form->addEmail('email', 'Váš e-mail:')
			->setRequired('Prosím vložte váš e-mail.');

		$form->addPassword('password', 'Zvolte si heslo:')
			->setOption('description', sprintf('alespoň %d znaků', self::PASSWORD_MIN_LENGTH))
			->setRequired('Prosím zvolte si heslo.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->userManager->add($values->username, $values->email, $values->password);
			} catch (Model\DuplicateNameException $e) {
				$form['username']->addError('Username is already taken.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
