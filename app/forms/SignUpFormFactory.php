<?php

namespace App\Forms;

use App\Model;
use Czubehead\BootstrapForms\BootstrapForm;
use Czubehead\BootstrapForms\Enums\RenderMode;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Html;


final class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 6;

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
			->setRequired('Prosím zvolte si uživatelské jméno.')
			->addRule(Form::PATTERN, 'Jméno obsahuje zakázané znaky.', '[\p{L}\d]*');

		$form->addText('email', 'Váš e-mail:')
			->setRequired('Prosím vložte váš e-mail.')
			->addRule(Form::EMAIL, 'Zadejte prosím emailovou adresu v platném formátu.');

		$form->addPassword('password', 'Zvolte si heslo:')
			->setOption(
				'description',
				sprintf('musí obsahovat alespoň:' . (string) Html::el('ul')
						->addHtml( Html::el('li')->addText('%d znaků'))
						->addHtml( Html::el('li')->addText('jedno velké písmeno'))
						->addHtml( Html::el('li')->addText('jedno malé písmeno'))
						->addHtml( Html::el('li')->addText('jednu číslici')),
					self::PASSWORD_MIN_LENGTH))
			->setRequired('Prosím zvolte si heslo.')
			->addRule($form::MIN_LENGTH, 'Heslo musí být dlouhé alespoň %d znaků', self::PASSWORD_MIN_LENGTH)
			->addRule(Form::PATTERN, 'Heslo musí obsahovat alespoň jedno velké písmeno', '.*[\p{Lu}].*')
			->addRule(Form::PATTERN, 'Heslo musí obsahovat alespoň jedno malé písmeno', '.*\p{Ll}.*')
			->addRule(Form::PATTERN, 'Heslo musí obsahovat alespoň jednu číslici', '.*[0-9].*')
		;

		$form->addPassword('passwordCheck', 'Heslo pro kontrolu:')
			->setRequired('Prosím zadejte heslo ještě jednou pro kontrolu jestli jsou stejná')
			->addRule(Form::EQUAL, 'Hesla nejsou stejná', $form['password']);

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->userManager->add($values->username, $values->email, $values->password);
			} catch (Model\DuplicateNameException $e) {
				$form['username']->addError('Uživatel s tímto jménem již existuje.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
