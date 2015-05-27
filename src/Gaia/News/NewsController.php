<?php namespace Gaia\News;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Gaia\News\NewsRequest;
use Gaia\Repositories\NewsRepositoryInterface;
use Gaia\Services\NewsService;
use App\Models\News;
use App\Models\Seo;
use Redirect;
use Auth;
use App;
use MediaLibrary;


class NewsController extends Controller {

	protected $newsRepos, $newsService, $authUser;


	/**
	 * Constructor: inject the news repository class to be used in all methods
	 * @return type
	 */
	public function __construct(NewsRepositoryInterface $newsReposInterface, NewsService $newsService )
	{
		$this->newsRepos   = $newsReposInterface;
		$this->newsService = $newsService;
		$this->authUser = Auth::user();
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(!$this->authUser->can('list-news'))
			App::abort(403, 'Access denied');

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
		if(!$this->authUser->can('create-edit-news'))
			App::abort(403, 'Access denied');

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
		if(!$this->authUser->can('create-edit-news'))
			App::abort(403, 'Access denied');

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
		if(!$this->authUser->can('create-edit-news'))
			App::abort(403, 'Access denied');

		//get the small preview thumb if image is uploaded
		$mediaItems = MediaLibrary::getCollection($news, $news->getMediaCollectionName(), []);
		(count($mediaItems))?$thumbUrl = $mediaItems[0]->getURL('thumb-xs'):$thumbUrl = null; 

		return view('admin.news.edit', ["news" => $news, "seo" => $news->seo, 'thumbUrl' => $thumbUrl]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(News $news, NewsRequest $request)
	{
		if(!$this->authUser->can('create-edit-news'))
			App::abort(403, 'Access denied');

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
		if(!$this->authUser->can('delete-news'))
			App::abort(403, 'Access denied');

		$this->newsRepos->delete($news->id);
	}

}
