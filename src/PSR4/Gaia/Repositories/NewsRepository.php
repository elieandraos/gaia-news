<?php namespace Gaia\Repositories; 

use App\Models\News;

class NewsRepository extends DbRepository implements NewsRepositoryInterface 
{
	
	protected $limit = 12;

	/**
	 * Returns all the news sorted by published_at
	 * @return NewsCollection
	 */
	public function getAll()
	{	
		return News::latest('published_at')->paginate($this->limit);
	}


	/**
	 * Returns one news by id
	 * @param int $id 
	 * @return News
	 */
	public function find($id)
	{
		return News::findOrFail($id);
	}


	/**
	 * Create a news object
	 * @param int NewsRequest $request 
	 * @return News
	 */
	public function create($input)
	{
		return News::create($input);
	}

	/**
	 * Update a news object
	 * @param int $id 
	 * @param type $input 
	 * @return News
	 */
	public function update($id, $input)
	{
		$news = $this->find($id);
		if(!isset($input['is_featured']))
			$input['is_featured'] = 0;
		
		return $news->update($input); 
	}


	/**
	 * Delete the news object
	 * @param int $id 
	 * @return 
	 */
	public function delete($id)
	{
		$news = $this->find($id);
		$news->delete();
	}

}

?>