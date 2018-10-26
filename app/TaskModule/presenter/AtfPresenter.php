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
use stdClass;

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

		if ($this->taskManager->taskExist($lesson, $order + 1))
			$next = [
				'lesson' => $lesson,
				'task'   => $order + 1,
				'title'  => 'Další úloha'
			];
		else if ($this->taskManager->taskExist($lesson + 1, 1))
			$next = [
				'lesson' => $lesson + 1,
				'task'   => 1,
				'title'  => 'Další lekce'
			];
		else
			$next = [
				'lesson' => '',
				'task'   => '',
				'title'  => ''
			];

		if ($this->taskManager->taskExist($lesson, $order - 1))
			$prev = [
				'lesson' => $lesson,
				'task'   => $order - 1,
				'title'  => 'Předchozí úloha'
			];
		else if ($this->taskManager->taskExist($lesson - 1, $this->taskManager->getMaxTask($lesson - 1)))
			$prev = [
				'lesson' => $lesson - 1,
				'task'   => $this->taskManager->getMaxTask($lesson - 1),
				'title'  => 'Předchozí lekce'
			];
		else
			$prev = [
				'lesson' => '',
				'task'   => '',
				'title'  => ''
			];

		$this->template->next = $next;
		$this->template->prev = $prev;
	}

	public function renderEditLesson($id)
	{
		$lesson = $this->taskManager->getLesson($id);
		if (!$lesson) {
			$this->error('Lekce nebyla nalezena');
		}
		$this['editLessonForm']->setDefaults($lesson->toArray());
	}

	public function renderEditTask($id)
	{

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

	public function taskFomSuccessed(BootstrapForm $form, stdClass $values)
	{
		$lesson = $this->getParameter('lesson');
		$order  = $this->getParameter('order');

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
			$contentLen = mb_strlen($content);
			$stats      = [
				'mistakes' => 0,
				'chars'    => $contentLen,
			];
			$answer     = $values['answer'];
			$answerLen  = mb_strlen($answer);

			if ($answerLen < $contentLen)
				$stats['mistakes'] = $contentLen - $answerLen;

			$wrong   = '';
			if (!empty($answer)) {
				$content = str_split($content);
				$answer  = str_split($answer);
				foreach ($answer as $i => $char) {
					if (!isset($content[$i]))
						$content[$i] = null;

					if ($content[$i] != $char) {
						$wrong .= "<span class=\"text-danger\">$char</span>";
						$stats['mistakes']++;
					} else
						$wrong .= $char;
				}
			}

			$this->template->wrong = $wrong;
			$this->template->stats = $stats;
		}
	}


	protected function createComponentEditLessonForm()
	{
		$form = new BootstrapForm();
		$form->addText('name', 'Jméno lekce:');
		$form->addSubmit('save', 'Uložit');
		$form->onSuccess[] = [$this, 'editLessonFormSucceeded'];

		return $form;
	}

	public function editLessonFormSucceeded(Form $form, stdClass $values)
	{
		$id = $this->getParameter('id');
		$this->flashMessage($id);

		if ($id) {
			$this->taskManager->updateLesson($id, $values);
			$this->flashMessage('Lekce byla úspěšně uložena');
		} else {
			$this->taskManager->addLesson($values);
			$this->flashMessage('Lekce byla úspěšně ');

		}
		$this->redirect('Atf:');
	}


}