<?php namespace Gaia\Services;

class NewsService
{
	

	/**
	 * Handles the image upload for the news
	 * @param type $news 
	 * @return type
	 */
	public function uploadImage($news, $uploaded_image)
	{
		$news->removeMediaCollection($news->getMediaCollectionName());
		
		$file = $uploaded_image;
		$tempDirectory = storage_path('temp');
		$fileName = $file->getClientOriginalName();

		$file->move($tempDirectory, $fileName);

		$collectionName = $news->getMediaCollectionName();
		$news->addMedia($tempDirectory . '/' . $fileName, $collectionName);
	}


	/**
	 * Removes the news image
	 * @param type $news 
	 * @return type
	 */
	public function removeImage($news)
	{
		$news->removeMediaCollection($news->getMediaCollectionName());
	}

}

?>