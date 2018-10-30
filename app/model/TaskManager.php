<?php
/**
 * Created by PhpStorm.
 * User: wujido
 * Date: 25.10.18
 * Time: 20:51
 */

namespace App\Model;


use Nette\Utils\Arrays;

class TaskManager extends BaseManager
{
	const LESSON_TABLE = 'atf_lesson';
	const TASK_TABLE = 'atf_task';

	public function getTask($lesson, $order)
	{
		return $this->database->table(self::TASK_TABLE)
			->where('lesson_id', $lesson)
			->where('order', $order)
			->fetch();
	}

	public function getTaskById($id)
	{
		return $this->database->table(self::TASK_TABLE)->get($id);
	}

	public function getLesson($lesson)
	{
		return $this->database->table(self::LESSON_TABLE)
			->where('lesson_id', $lesson)
			->fetch();
	}

	public function getLessons()
	{
		$lessons = $this->database->table(self::LESSON_TABLE)->fetchAll();
		$return = [];
		foreach ($lessons as $lesson) {
			$id = $lesson->lesson_id;
			$tasks     = $this->database->table(self::TASK_TABLE)
				->where('lesson_id', $id)
				->order('order')
				->fetchAll();

			$return[$id][0] = $lesson;
			foreach ($tasks as $task) {
				$return[$id][$task->order] = $task;

			}
		}

		return $return;
	}

	public function getMaxTask($lesson)
	{
		return $this->database->table(self::TASK_TABLE)
			->where('lesson_id', $lesson)
			->max('order');
	}

	public function taskExist($lesson, $order)
	{
		$count = $this->database->table(self::TASK_TABLE)
			->where('lesson_id', $lesson)
			->where('order', $order)
			->count();
		if ($count == 1)
			return true;
		return false;
	}

	public function addLesson($values)
	{
		$this->database->table(self::LESSON_TABLE)
			->insert($values);
	}

	public function updateLesson($id, $values)
	{
		$this->database->table(self::LESSON_TABLE)
			->where('lesson_id', $id)
			->update($values);
	}

	public function addTask($values)
	{
		$this->database->table(self::TASK_TABLE)
			->insert($values);
	}

	public function updateTask($id, $values)
	{
		$this->database->table(self::TASK_TABLE)
			->where('task_id', $id)
			->update($values);
	}


	public function saveOrder($table, $order, $taskId, $condition = null, ...$params)
	{
		$selection = $this->database->table($table);

		if (!empty($condition)) {
			$selection->where($condition, $params);
		}


		$rows = $selection->fetchAll();
		foreach ($rows as $item) {
			$all[] = $item->task_id;
		}


//		return $all;

		$was = true;
		for ($i = 0; $i <= count($all); $i++) {
			if (isset($all[$i])) {
				if ($all[$i] < $order && $was) {
					$finalOrder[$i] = $all[$i];
				} else if ($all[$i] == $order) {
					$finalOrder[$i + 1] = $taskId;
					$finalOrder[$i] = $all[$i];
					$i++;
					$was = false;
				} else if (!$was) {
					$finalOrder[$i] = $all[$i - 1];
				} else {
					$finalOrder[$i] = $all[$i];
				}
			}
		}


		foreach ($finalOrder as $order => $id) {
			$selection->where('task_id', $id)
				->update(['order' => $order]);
		}
	}

	public function getLessonOrder($id = 1)
	{
		$selection = $this->database->table(self::TASK_TABLE)
			->where('lesson_id', $id)
			->order('order');

		foreach ($selection->fetchAll() as $task) {
			$order          = $task->order;
			$return[$order] = $order;
		}

		if ($selection->count() == 0)
			$return[1] = 1;

		return $return;
	}
	
}