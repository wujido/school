<?php
/**
 * Created by PhpStorm.
 * User: wujido
 * Date: 25.10.18
 * Time: 20:59
 */

namespace App\Model;


use Nette\Database\Context;

abstract class BaseManager
{
	/**
	 * @var Context
	 */
	protected $database;

	public function __construct(Context $database)
	{
		$this->database = $database;
	}

}