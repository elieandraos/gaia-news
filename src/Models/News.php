<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\MediaLibrary\MediaLibraryModel\MediaLibraryModelInterface;
use Spatie\MediaLibrary\MediaLibraryModel\MediaLibraryModelTrait;
use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

class News extends Model implements MediaLibraryModelInterface, TranslatableContract {

	use MediaLibraryModelTrait, Translatable;

	protected $table = 'news';
	protected $fillable = ['title', 'excerpt', 'description', 'slug', 'youtube_url', 'published_at', 'category_id', 'is_featured'];
	protected $translatedAttributes = ['title', 'excerpt', 'description'];
	protected $translator = 'App\Models\NewsTranslation';
	
	
	/**********************
	 * ELOQUANT RELATIONS *
	 **********************/
	
	public function category()
	{
		return $this->belongsTo('App\Models\Category');
	}

	/**
	 * Morphing to Seo Model
	 * @return type
	 */
	public function seo()
	{
	    return $this->morphOne('App\Models\Seo', 'seoable');
	}


	/******************
	 * DATE FUNCTIONS *
	 ******************/

	/**
	 * published_at mutator: parse the date before saving the model 
	 * @param type $date 
	 * @return type
	 */
	public function setPublishedAtAttribute($date)
	{
		$this->attributes['published_at'] = Carbon::parse($date);
	}


	/**
	 * returns a friendly date format for pusblished_at attrubute
	 * @return type
	 */
	public function getHumanPublishedAt()
    {
        return Carbon::parse($this->published_at)->diffForHumans();
    }


    /**
	 * returns a friendly date format for pusblished_at attrubute
	 * @return type
	 */
	public function getReadablePublishedAt()
    {
        return Carbon::parse($this->published_at)->format('M d,Y');
    }


    /**
     * Return the media collection name
     * @return type
     */
    public function getMediaCollectionName()
    {
    	return "collection-".$this->id;
    }


    /*****************
	 * MEDIA LINRARY *
	 *****************/

    /**
     * Image profiles: list of resized images post uploading
     * @return type
     */
	public function getImageProfileProperties()
	{
	    return [
	    	//front end thumbs
	        'featured' 		  => ['w'=>670, 'h'=>382],
	        'thumb-large'     => ['w'=>570, 'h'=>325],
	        'thumb-medium'    => ['w'=>270, 'h'=>192],
	        'thumb-small'     => ['w'=>170, 'h'=>120],
	        //social media sharing thumbs
	        'facebook' => ['w'=>128, 'h'=>128],
	        'twitter'  => ['w'=>128, 'h'=>128],
	        //backend
	        'thumb-xs' => ['w'=>60, 'h'=>60]
	    ];
	}  



	/**
	 * Returns the root category id configured for the news
	 * @return type
	 */
	public static function getConfiguredRootCategory()
	{
		$obj = CategoryModules::where('is_news', '=', 1)->first();
		
		if(!$obj)
			return null;

		return $obj->category_id;
	}  


}
