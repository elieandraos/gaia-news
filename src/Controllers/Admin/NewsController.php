<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\NewsRequest;
use Gaia\Repositories\NewsRepositoryInterface;
use Redirect;


class NewsController extends Controller {

	protected $newsRepos;


	/**
	 * Constructor: inject the news repository class to be used in all methods
	 * @return type
	 */
	public function __construct(NewsRepositoryInterface $newsReposInterface )
	{
		$this->newsRepos = $newsReposInterface;
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$news = $this->newsRepos->getAll();
		return view('admin.news.index', ["news" => $news]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.news.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(NewsRequest $request)
	{
		$input = $request->all();
		$this->newsRepos->create($input); 
		return Redirect::route('admin.news.list');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$news = $this->newsRepos->find($id);
		return view('admin.news.edit', ["news" => $news]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, NewsRequest $request)
	{
		$input = $request->all();
		$this->newsRepos->update($id, $input); 
		return Redirect::route('admin.news.list');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->newsRepos->delete($id);
	}

}
