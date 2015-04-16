<?php namespace Gaia\News;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Gaia\News\NewsRequest;
use Gaia\Repositories\NewsRepositoryInterface;
use Gaia\Services\NewsService;
use App\Models\News;
use App\Models\Seo;
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
		$seo = new Seo;
		return view('admin.news.create', ['seo' => $seo]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(NewsRequest $request)
	{
		$input = $request->all();
		//create the news
		$news = $this->newsRepos->create($input); 
		//upload the image via service
		if(isset($input['image']))
			$this->newsService->uploadImage($news, $input['image']);
		//add seo polymorphic model
		$seo = new Seo;
		$seo->updateFromInput($input);
		$news->seo()->save($seo);

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
		// ($news->seo) : $seo = $news->seo
		return view('admin.news.edit', ["news" => $news, "seo" => $news->seo]);
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

		$news->seo->updateFromInput($input);

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
