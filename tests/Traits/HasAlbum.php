<?php  

namespace Test\Traits;

trait HasAlbum
{
	private $album;

	protected function bootHasAlbum()
	{
		 if(!$this->profile){
            throw new \Exception("profile not defined.");
        }

        $this->album = factory(\App\Album::class)->create();
        
	}

	 protected function tearDownHasAlbum()
    {
        if($this->album){
            $this->album->delete();
        }
    }
}

?>