<?php namespace Gaia\Services;

use Image;
use File;

class NewsService
{
	
	/**
	 * Returns the upload path (and creates it recursively)
	 * @param type $news 
	 * @return type
	 */
    public function getUploadsPath($news)
	{
		$path = public_path()."/uploads/news/".$news->id."/";
		File::exists($path) or File::makeDirectory($path, 0755, true);
		return $path;
	}


	/**
	 * Handles the image upload for the news
	 * @param type $news 
	 * @return type
	 */
	public function uploadImage($news, $uploaded_image)
	{
		$filename = NULL;
		if($uploaded_image && $news)
		{
			$image = Image::make($uploaded_image->getRealPath()); 
			$filename = $uploaded_image->getClientOriginalName();
			$image->save($this->getUploadsPath($news).$filename);
			$image->resize(64, 64, function($constraint){ $constraint->aspectRatio(); });
			$image->save($this->getUploadsPath($news)."thumb-xs-".$filename);
		}
		return $filename;
	}

	
	/**
	 * Removes the image
	 * @param type $news 
	 * @return type
	 */
	public function removeImage($news)
	{
		if($news && $news->image)
		{
			$path = $this->getUploadsPath($news);
			File::deleteDirectory($path);
		}
	}

}

?>