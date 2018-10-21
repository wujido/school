<?php

namespace App\Forms;

use Czubehead\BootstrapForms\Enums\RenderMode;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->renderMode = RenderMode::SideBySideMode;
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Prosím vložte vaše uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím vložte vaše heslo.');

		$form->addCheckbox('remember', 'Zůstat přihlášen');

		$form->addSubmit('send', 'Přihlásit se');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('Uživatelské jméno nebo heslo jsou neplatné');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
