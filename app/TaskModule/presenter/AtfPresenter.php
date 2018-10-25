<?php
/**
 * Created by PhpStorm.
 * User: wujido
 * Date: 21.10.18
 * Time: 22:21
 */

namespace App\Presenters;


use App\Model\TaskManager;
use Czubehead\BootstrapForms\BootstrapForm;
use Nette\Forms\Form;

class AtfPresenter extends BasePresenter
{
	/**
	 * @var TaskManager
	 */
	private $taskManager;

	public function __construct(TaskManager $taskManager)
	{

		$this->taskManager = $taskManager;
	}

	public function renderDefault()
	{
		$this->template->lessons = $this->taskManager->getLessons();
	}

	public function renderTask($lesson, $order)
	{
		$this->template->task = $this->taskManager->getTask($lesson, $order);
	}

	public function createComponentTaskForm()
	{
		$content = $this->taskManager->getTask(
			$this->getParameter('lesson'),
			$this->getParameter('order')
		)->content;

		$form = new BootstrapForm();
		$form->addTextArea('answer', $content);
		$form->addSubmit('check', 'Zkontrolovat');

		$form->onSuccess[] = [$this, 'taskFomSuccessed'];

		return $form;
	}

	public function taskFomSuccessed(BootstrapForm $form, \stdClass $values)
	{
		$lesson = $this->getParameter('lesson');
		$order   = $this->getParameter('order');

		$content = $this->taskManager->getTask(
			$lesson,
			$order
		)->content;
		if ($values->answer == $content) {
			$this->flashMessage('Bez jediné chybičky');
			if ($this->taskManager->taskExist($lesson, $order + 1))
				$this->redirect('Atf:task', $lesson, $order + 1);
			else
				$id = $this->taskManager->getLesson($lesson)->lesson_id;
				$this->flashMessage("$id. lekce úspěšně dokončena");
				$this->redirect('Atf:');
		} else {
			$content = str_split($content);
			$answer = str_split($values['answer']);
			$wrong = '';
			foreach ($content as $i => $char) {
				if (!isset($answer[$i]))
					$answer[$i] = '';

				if ($answer[$i] != $char)
					$wrong .= "<span class=\"text-danger\">$content[$i]</span>";
				else
					$wrong .= $char;
			}
			$this->template->wrong = $wrong;
		}
	}

}