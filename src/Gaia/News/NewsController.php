<?php namespace Gaia\News;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Gaia\News\NewsRequest;
use Gaia\Repositories\NewsRepositoryInterface;
use Gaia\Services\NewsService;
use App\Models\News;
use Redirect;


class NewsController extends Controller {

	protected $newsRepos, $newsService;


	/**
	 * Constructor: inject the news repository class to be used in all methods
	 * @return type
	 */
	public function __construct(NewsRepositoryInterface $newsReposInterface, NewsService $newsService )
	{
		$this->newsRepos   = $newsReposInterface;
		$this->newsService = $newsService;
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
		$news = $this->newsRepos->create($input); 
		$this->newsService->uploadImage($news, $input['image']);
		return Redirect::route('admin.news.list');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(News $news)
	{
		return view('admin.news.edit', ["news" => $news]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(News $news, NewsRequest $request)
	{
		$input = $request->all();
		
		//reset the input image
		if(isset($input['remove_image']) && !isset($input['image']))
			$input['image'] = null;
		//remove image if checkbox is ticked
		if(isset($input['remove_image']))
			$this->newsService->removeImage($news);
		//update the database object
		$this->newsRepos->update($news->id, $input);
		//upload new picture if any 
		if(isset($input['image']))
			$this->newsService->uploadImage($news, $input['image']);

		return Redirect::route('admin.news.list');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(News $news)
	{
		$this->newsService->removeImage($news);
		$this->newsRepos->delete($news->id);
	}

}
