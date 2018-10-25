<?php
/**
 * Created by PhpStorm.
 * User: wujido
 * Date: 25.10.18
 * Time: 20:51
 */

namespace App\Model;


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
}