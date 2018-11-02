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

	const NEXT_TASK_TITLE = 'Další úloha';
	const NEXT_LESSON_TITLE = 'Další lekce';
	const PREV_TASK_TITLE = 'Předchozí úloha';
	const PREV_LESSON_TITLE = 'Předchozí lekce';

	const LESSON_NOT_FOUND = 'Lekce nebyla nalezena';
	const TASK_NOT_FOUND = 'Úloha nebyla nalezena';

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
				'title'  => self::NEXT_TASK_TITLE
			];
		else if ($this->taskManager->taskExist($lesson + 1, 1))
			$next = [
				'lesson' => $lesson + 1,
				'task'   => 1,
				'title'  => self::NEXT_LESSON_TITLE
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
				'title'  => self::PREV_TASK_TITLE
			];
		else if ($this->taskManager->taskExist($lesson - 1, $this->taskManager->getMaxTask($lesson - 1)))
			$prev = [
				'lesson' => $lesson - 1,
				'task'   => $this->taskManager->getMaxTask($lesson - 1),
				'title'  => self::PREV_LESSON_TITLE
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
			$this->error(self::LESSON_NOT_FOUND);
		}
		$this['editLessonForm']->setDefaults($lesson->toArray());
	}

	public function renderEditTask($id)
	{
		$task = $this->taskManager->getTaskById($id);
		if (!$task) {
			$this->error(self::TASK_NOT_FOUND);
		}
		$default = $task->toArray();
		$default['lesson'] = $default['lesson_id'];
		$default['order_select'] = $default['order'];
		$this['editTaskForm']->setDefaults($default);

//		$this->taskManager->saveOrder(taskManager::TASK_TABLE, 1, $id, 'lesson_id', 1);
	}

	public function renderAddTask($lesson, $order)
	{
		$this['editTaskForm']->setDefaults(['order' => $order, 'order_select' => $order]);
	}

	public function createComponentTaskForm()
	{
		$content = $this->taskManager->getTask(
			$this->getParameter('lesson'),
			$this->getParameter('order')
		)->content;

		$form = new BootstrapForm();
		$form->addText('answer', $content)
			->setRequired('Vyplň prosím obsah úlohy');
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

//			if ($answerLen < $contentLen)
//				$stats['mistakes'] = $contentLen - $answerLen;

			$wrong   = '';
			if (!empty($answer)) {
				$content = preg_split('//u', $content, -1, PREG_SPLIT_NO_EMPTY);
				$answer  = preg_split('//u', $answer, -1, PREG_SPLIT_NO_EMPTY);

				if (count($content) > count($answer)) {
					$cycle = $content;
					$control = $answer;
				}
				else {
					$cycle = $answer;
					$control = $content;
				}

				foreach ($cycle as $i => $char) {
					if (!isset($control[$i]))
						$control[$i] = null;

					if ($control[$i] != $char) {
						$vypustka   = '<span style="font-size: 6px">…</span>';
						$wrong .= "<span class=\"text-danger\">" . (isset($control[$i]) ? ($control[$i] == ' ') ? $vypustka : $control[$i]  : ($char == ' ') ? $vypustka : $char) . "</span>";
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
		$form->onSuccess[] = [$this, 'editLessonFormSuccessed'];

		return $form;
	}

	public function editLessonFormSuccessed(BootstrapForm $form, stdClass $values)
	{
		$id = $this->getParameter('id');

		if ($id) {
			$this->taskManager->updateLesson($id, $values);
			$this->flashMessage('Lekce byla úspěšně uložena');
		} else {
			$this->taskManager->addLesson($values);
			$this->flashMessage('Lekce byla úspěšně přidána');

		}
		$this->redirect('Atf:');
	}

	public function createComponentEditTaskForm()
	{
		$id = $this->getParameter('lesson');

		if ($id) {
			$tasks = $this->taskManager->getLessonOrder($id);
			$i         = end($tasks) + 1;
			$tasks[$i] = $i;
			$tasks = array_diff($tasks, [0]);
			$lessons = [$id => $id];
		}
	else {
		$tasks     = $this->taskManager->getLessonOrder();

			$lessons = $this->taskManager->getLessons();

			foreach ($lessons as $name => $lesson) {
				$lessons[$name] = $name;
			}
		}



		$form = new BootstrapForm();
		$form->addText('name', 'Jméno');
		$form->addTextArea('content', 'Obsah');
		$form->addSelect('lesson', 'Lekce', $lessons)
			->setDisabled();
		$form->addHidden('lesson_id');
		$form->addSelect('order_select', 'Pořadí v lekci', $tasks)
		->setDisabled();
		$form->addHidden('order');

		$form->addSubmit('save', 'Uložit');

		$form->onSuccess[] = [$this, 'editTaskFormSuccessed'];

		return $form;
	}

	public function editTaskFormSuccessed(BootstrapForm $form, stdClass $values)
	{
		$id = $this->getParameter('id');

		if ($id) {
//			$this->taskManager->saveOrder(taskManager::TASK_TABLE, $values->order, $id, 'lesson_id', $values->lesson_id);
			unset($values['order']);
			$this->taskManager->updateTask($id, $values);
			$this->flashMessage('Úloha byla úspěšně uložena');
		} else {
			$this->taskManager->addTask($values);
			$this->flashMessage('Úloha byla úspěšně přidána');
		}

		$this->redirect('Atf:');
	}

}