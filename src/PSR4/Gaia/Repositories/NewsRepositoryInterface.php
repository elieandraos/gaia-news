<?php 
	namespace Gaia\Repositories;
	use App\Http\Requests\Admin\NewsRequest;

	interface NewsRepositoryInterface
	{
		public function getAll($limit);
		public function find($id);
		public function create($input);
		public function update($id, $input);
		public function delete($id);
	}
?>
