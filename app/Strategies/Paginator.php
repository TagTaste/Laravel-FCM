<?php namespace app\Strategies;


class Paginator
{
    public $take;
    
    public static function paginate($page = null,$take = null) : array
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
        $this->take = $take ?? 20;
        $this->page = $page ?? 1;
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