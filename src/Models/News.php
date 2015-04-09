<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class News extends Model {

	protected $table = 'news';
	protected $fillable = ['title', 'excerpt', 'description', 'image', 'slug', 'published_at'];
	protected $hidden = [];

	
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
	 * image mutator: save the image original name as string 
	 * @param type $date 
	 * @return type
	 */
	public function setImageAttribute($image)
	{
		if($image) 
			$this->attributes['image'] = $image->getClientOriginalName();
		else
			$this->attributes['image'] = null;
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
     * Returns the image thumb relative url
     * @param type $size 
     * @return type
     */
    public function getThumbUrl($size = 'xs')
    {
    	return "uploads/news/".$this->id."/thumb-".$size."-".$this->image;
    }


}
