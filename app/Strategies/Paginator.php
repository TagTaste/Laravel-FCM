<?php namespace App\Strategies;


class Paginator
{
    public $take;
    
    /**
     * @param null $page
     * @param null $take
     * @return array
     */
    public static function paginate($page = null, $take = null) : array
    {
        $self = new self($page,$take);
        return $self->getSkipTake();
    }
    
    /**
     * Paginator constructor.
     * @param null $page
     * @param null $take
     */
    private function __construct($page = null, $take = null)
    {
        $this->page = $page ?? 1;
        // $this->take = $take ?? 20;
        $this->take = $take ? ($take > 20 ? 20 : $take) : 20;
        $this->skip = $this->calculateSkip();
    }
    
    private function calculateSkip() : ?int
    {
        return $this->page > 1 ? ($this->page - 1) * $this->take : 0;
    }
    
    public function getSkipTake() : array
    {
        return [$this->skip,$this->take];
    }
}