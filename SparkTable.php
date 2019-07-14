<?php
namespace Sparkout;

class SparkTable {

    private $columns = array();
    private $whereList = array();
    private $sortable = array();
    private $posts = array();
    private $totalData = 0;
    private $totalFiltered = 0;

    public function __construct($table = null,$request = null) {
        $this->table = $table;
        $this->request = $request;
    }

    public function columns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function sortable($sortable = null)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function where(array $where)
    {
        $this->whereList = $where;
        return $this;
    }

    public function process()
    {
        $this->totalData = $this->table->count();
		$limit = $this->request->length;
		$start = $this->request->start;
		$order = $this->columns[0];
		$dir = $this->request->input('order.0.dir');
        
        $post_query = $this->table;

        if (!$this->whereList || sizeof($this->whereList) > 0){
            foreach ($this->whereList as $key => $where) {
                $key = (string) $where['key'];
                $value = (string) $where['value'];
                $symbol = (string) $where['symbol'];
                if ($where['or'] == true) {
                    $post_query = $post_query->orWhere($key,$symbol,$value);
                } else {
                    $post_query = $post_query->where($key,$symbol,$value);
                }
            }
        }

		if(empty($this->request->input('search.value'))){

            $this->posts = $post_query->offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
			$this->totalFiltered = $this->table->count();
		}else{
            $search = $this->request->input('search.value');
            
            $search_query = $post_query;

            $is_where = true;
            foreach ($this->columns as $key => $coloumn) {
                $coloumn = (string) $coloumn;
                if (in_array($coloumn, $this->sortable))
                {
                    if ($is_where == true) {
                        $search_query = $search_query->where($coloumn,'like',"%{$search}%");
                        $is_where = false;
                    } else {
                        $search_query = $search_query->orWhere($coloumn,'like',"%{$search}%");
                    }
                }
            }

			$this->posts = $search_query->offset($start)
							->limit($limit)
							->orderBy($order, $dir)
							->get();
			$this->totalFiltered = $search_query->count();
		}		
					
		return $this;
        
    }

    public function render($results = null)
    {
        $data = array();
       
		
		if($this->posts){
			foreach($this->posts as $r){

                foreach ($results as $key => $result) {
                    $key = (string) $result['key'];
                    if (isset($result['value'])) {
                        $value = $result['value'];
                    } else {
                        $value = $result['key'];
                    }
                    
                    if ($result['html'] == true) {
                        $nestedData[$key] = $result['start'].$r->$value.$result['end'];
                    } else {
                        $nestedData[$key] = $r->$value;
                    }
                    
                }
                $data[] = $nestedData;
                
			}
		}
		
		$json_data = array(
			"draw"			=> intval($this->request->input('draw')),
			"recordsTotal"	=> intval($this->totalData),
			"recordsFiltered" => intval($this->totalFiltered),
			"data"			=> $data
		);
		
		return json_encode($json_data);
    }

}