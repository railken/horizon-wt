<?php

namespace Api\Helper;


class Paginator{

	/**
	 * Retrieve
	 *
	 * @return New instance
	 */
	public static function retrieve($query, $page, $take)
	{
		return (new self)
			->setQuery($query)
			->setPage($page)
			->setTake($take)
			->execute();
	}

	/**
	 * Setter
	 *
	 * @param integer $total
	 *
	 * @return $this
	 */
	public function setQuery($query)
	{
		$this->query = clone $query;
		return $this;
	}

	/**
	 * Getter
	 *
	 * @return integer
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * Setter
	 *
	 * @param integer $total
	 *
	 * @return $this
	 */
	public function setTotal($total)
	{
		$this->total = $total;
		return $this;
	}

	/**
	 * Getter
	 *
	 * @return integer
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 * Setter
	 *
	 * @param integer $take
	 *
	 * @return $this
	 */
	public function setTake($take)
	{
		$this->take = $take;
		$this->show = $take;
		return $this;
	}

	/**
	 * Getter
	 *
	 * @return integer
	 */
	public function getTake()
	{
		return $this->take;
	}

	/**
	 * Setter
	 *
	 * @param integer $page
	 *
	 * @return $this
	 */
	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}

	/**
	 * Getter
	 *
	 * @return integer
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * Execute
	 *
	 * @return this
	 */
	public function execute()
	{
		$total = $this->query->count();
		$this->setTotal($total);

		$page = $this->getPage();
		$first = ($page - 1) * $this->getTake();
		$last = $first + $this->getTake();

		if($last > $total)
			$last = $total;

		$this->setMaxResults($this->getTake());
		$this->setFirstResult($first);
		$this->setFrom($first);
		$this->setTo($last);

		$this->setPages(ceil($total / $this->getTake()));

		return $this;
	}

	public function setMaxResults($max_results)
	{
		$this->max_results = $max_results;
	}

	public function setFirstResult($first_result)
	{
		$this->first_result = $first_result;
	}

	public function getMaxResults()
	{
		return $this->max_results;
	}

	public function getFirstResult()
	{
		return $this->first_result;
	}

	public function setPages($pages)
	{
		$this->pages = $pages;
	}

	public function setFrom($from)
	{
		$this->from = $from;
	}

	public function setTo($to)
	{
		$this->to = $to;
	}
}
