<?php namespace Gaia\News;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Gaia\News\NewsRequest;
use Gaia\Repositories\NewsRepositoryInterface;
use Gaia\Repositories\PostTypeRepositoryInterface;
use Gaia\Services\NewsService;
//Models
use App\Models\News;
use App\Models\Seo;
use App\Models\Locale;
use App\Models\Category;
//Facades
use Redirect;
use Auth;
use App;
use MediaLibrary;
use Config;
use Flash;
use View;


class NewsController extends Controller {

	protected $newsRepos, $newsService, $authUser, $locales, $categories;


	/**
	 * Constructor: inject the news repository class to be used in all methods
	 * @return type
	 */
	public function __construct(NewsRepositoryInterface $newsReposInterface, NewsService $newsService, PostTypeRepositoryInterface $postTypeRepositoryInterface)
	{
		$this->newsRepos   = $newsReposInterface;
		$this->newsService = $newsService;
		$this->authUser = Auth::user();

		//localization
		$this->locales = Locale::where('language', '!=', 'en')->lists('language', 'language');
		$this->first_locale = array_first($this->locales, function(){return true;});

		//news category root 
		$categoryRootId = News::getConfiguredRootCategory();
		$categories = Category::find($categoryRootId)->descendants()->get();
		foreach($categories as $category)
			$this->categories[$category->id] = $category->title;

		//share the post type submenu to the layout
		$this->postTypeRepos = $postTypeRepositoryInterface;
		View::share('postTypesSubmenu', $this->postTypeRepos->renderMenu());
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(!$this->authUser->can('list-news') && !$this->authUser->is('superadmin'))
			App::abort(403, 'Access denied');

		$news = $this->newsRepos->getAll();
		return view('admin.news.index', ["news" => $news, "locale" => $this->first_locale]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(!$this->authUser->can('create-edit-news') && !$this->authUser->is('superadmin'))
			App::abort(403, 'Access denied');

		$seo = new Seo;
		return view('admin.news.create', ['seo' => $seo, 'thumbUrl' => null, "categories" => $this->categories]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(NewsRequest $request)
	{
		if(!$this->authUser->can('create-edit-news') && !$this->authUser->is('superadmin'))
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

		Flash::success('News was created successfully.');
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
		if(!$this->authUser->can('create-edit-news') && !$this->authUser->is('superadmin'))
			App::abort(403, 'Access denied');

		$seo = $news->seo;
		//get the small preview thumb if image is uploaded
		$mediaItems = MediaLibrary::getCollection($news, $news->getMediaCollectionName(), []);
		(count($mediaItems))?$thumbUrl = $mediaItems[0]->getURL('thumb-xs'):$thumbUrl = null; 

		return view('admin.news.edit', ["news" => $news, "seo" => $news->seo, 'thumbUrl' => $thumbUrl, "categories" => $this->categories]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(News $news, NewsRequest $request)
	{
		if(!$this->authUser->can('create-edit-news') && !$this->authUser->is('superadmin'))
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

		Flash::success('News was updated successfully.');
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
		if(!$this->authUser->can('delete-news') && !$this->authUser->is('superadmin'))
			App::abort(403, 'Access denied');

		$this->newsRepos->delete($news->id);
	}


	/**
	 * Translate the translatable fields 
	 * @param type $news 
	 * @return type
	 */
	public function translate($news, $locale)
	{
		if(!$this->authUser->can('translate-news') && !$this->authUser->is('superadmin'))
			App::abort(403, 'Access denied');

		App::setLocale($locale);
		$seo = $news->seo;

		return view('admin.news.translate', ["news" => $news, "seo" => $news->seo, 'locales' => $this->locales, 'locale' => $locale]);
	}


	/**
	 * Save the translated content of the news
	 * @param type $news 
	 * @param type $locale 
	 * @return type
	 */
	public function translateStore(NewsRequest $request, $news, $locale)
	{
		if(!$this->authUser->can('translate-news') && !$this->authUser->is('superadmin'))
			App::abort(403, 'Access denied');

		App::setLocale($locale);
		$input = $request->all();
		$this->newsRepos->update($news->id, $input);
		$news->seo->updateFromInput($input);
		App::setLocale("en");
		
		Flash::success('News was translated successfully.');
		return Redirect::route('admin.news.list');
	}

}
