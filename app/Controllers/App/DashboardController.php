<?php 
namespace App\Controllers\App; 
use CodeIgniter\Controller;
use Config\Services\request;
use App\Models\PneumaticPair;
use CodeIgniter\API\ResponseTrait;
use App\Models\PneumaticPairData;
  
class DashboardController extends Controller
{
    use ResponseTrait;
    protected $db;
    function __construct() { 
        $this->db = \Config\Database::connect();
    } 
    public function index()
    {
        if ($this->request->is('get')) {
            $pneumatic_pair = new PneumaticPair();
                $data = [
                    'pairs' => $pneumatic_pair->paginate(6),
                    'pager' => $pneumatic_pair->pager
                ];
		
            echo view('Application/dashboard',$data);
        } elseif ($this->request->is('post')) {
            $session = session();
       
        }
        
    }

    public function getData()
    {
        helper('pager');
        helper('string');
        helper('inflector');
        // return $this->respond('<tr><th>1</th></tr>');
        // Get filter value
        try{
            $filter_name = '';
        //$filter_name = $this->request->getPost('filter_name');
        
        // Get current page
        $page = $this->request->getPost('page') ?? 1;
        
         $pneumatic_pair = new PneumaticPair();
         $rows =  $pneumatic_pair->countAll();
        // return $this->respond($pneumatic_pair->countAll());
        // Set pagination configuration
        $config = [
            'baseURL' => base_url() . 'get_data',
            'totalRows' => $pneumatic_pair->countAll(),
            'perPage' => 10,
            'uriSegment' => 3,
            'current' => $page
        ];
       // $pager->initialize($config);
        
        // Get filtered and paginated data
        //$data = $this->pneumatic_pair->get_filtered_data($filter_name, $config['perPage'], ($page - 1) * $config['perPage']);
        //$data = $pneumatic_pair->paginate(5,'',$page);
        $pager = \Config\Services::pager(null,null,true);
        $limit = 7; // Items per page
        $offset = ($page - 1) * $limit;
        $links = $pager->makeLinks($page,$limit,$rows);
        
        // if (!empty($search)) {
        //     $pneumatic_pair->like('id', $filter_name);
        // }
        // $db = \Config\Database::connect();
        // $data =  $this->db->select('*')
        // ->from('pneumatic_pair')
        // ->join('pneumatic_pair', 'pneumatic_pair.id = pneumatic_pair_data.pair_id', 'left')
        // ->get()->getResult();
        $builder = $pneumatic_pair->builder();
        $db = db_connect();
        $sql = 'SELECT * FROM pneumatic_pair order by id desc';
        //$data = $pneumatic_pair->pair_records()->get()->getResult();

        // $db = \Config\Database::connect();
        // $builder = $db->table('pneumatic_pair');
        // $builder->join('pneumatic_pair_data','pneumatic_pair_data.pair_id = pneumatic_pair.id');
        // $data = $builder->get()->getResult();

        $query = $this->db->query('SELECT * FROM pneumatic_pair INNER JOIN pneumatic_pair_data ON pneumatic_pair.id = pneumatic_pair_data.pair_id ');
        // $this->db->from('pneumatic_pair');
        // $this->db->join('pneumatic_pair_data', 'pneumatic_pair_data.pair_id = pneumatic_pair.id');
        //$this->db->where('city', array('city.city_name' => 'Bangalore'));
       // $this->db->where('city.city_name', 'Bangalore');
        $data = $query->getResult();
       // $data = $pneumatic_pair->innerJoin('pneumatic_pair_data', 'pneumatic_pair_data.pair_id = pneumatic_pair.id')->limit($limit, $offset)->get()->getResult();
       // $data = $pneumatic_pair->getFilteredData($filter_name,$limit,$offset);
        // return $data;
       // return $data;
        // Generate table data
        return $this->respond($data);
        $table_data = '';
        foreach ($data as  $row) {
            $table_data .= '<tr>';
            $table_data .= '<th>' . $row->id . '</th>';
            $table_data .= '<td>' . $row->left_rfid . '</td>';
            $table_data .= '<td>' . $row->right_rfid . '</td>';
            if($row->pair_status == 1){
                $table_data .= '<td><button class="btn btn-info btn-outline-info "><i class="icofont icofont-info-square"></i>Accepted</button></td>';
            }
            else if($row->pair_status == 2){
                $table_data .= '<td><button class="btn btn-warning btn-outline-warning"><i class="icofont icofont-warning-alt"></i>Rejected</button></td>';
            }
            else if($row->pair_status == 3){
                $table_data .= '<td><button class="btn btn-success btn-outline-success"><i class="icofont icofont-check-circled"></i>Finished</button></td>';
            }
            $table_data .= '</tr>';
        }
        // Generate pagination links
        //$pagination = $this->pager->links();
    //    $pager->setPath('PneumaticPair/getFilteredData');
    //    $pager->setSegment(3);
    //    $pager->setPerPage($limit);
        
        // Return JSON response
        return $this->response->setJSON([
            'table_data' => $table_data,
            'links'=>$links
        ]);
    } catch (\Throwable $e) {
        return $this->respond($e->getMessage());
    }
    }
    public function demolive_list(Request $request)
	{	
		if($request->isMethod('get')){
		$data['questions'] = Demolivequestion::select('demolive_questions.*','QC.name as category','tbl.name as subcategory','tbl2.name as category2','tbl3.name as subcategory2')
											->leftjoin('question_categories as QC','demolive_questions.category','QC.id')
											->leftjoin('question_categories as tbl','demolive_questions.subcategory','tbl.id')
											->leftjoin('question_categories as tbl2','demolive_questions.category2','tbl2.id')
											->leftjoin('question_categories as tbl3','demolive_questions.subcategory2','tbl3.id')
                                                                                        ->orderBy('id','desc')
                                                                                        ->paginate(50);
		
		
		if($request->ajax()){
            return view('admin.demolive_list_pagination ',$data); 
        } 
		$data['curated'] = Demolivequestion::where('priority', 1)->count();
		$data['pageTitle'] = "Live Question List";
        return view("admin.demolive_list", $data);
		}
		elseif($request->isMethod('post')){
			$query = Demolivequestion::select('demolive_questions.*','QC.name as category','tbl.name as subcategory','tbl2.name as category2','tbl3.name as subcategory2')
											->leftjoin('question_categories as QC','demolive_questions.category','QC.id')
											->leftjoin('question_categories as tbl','demolive_questions.subcategory','tbl.id')
											->leftjoin('question_categories as tbl2','demolive_questions.category2','tbl2.id')
											->leftjoin('question_categories as tbl3','demolive_questions.subcategory2','tbl3.id');
			if($request->search_term){
				$query->where('demolive_questions.title','like','%'.$request->search_term.'%')
				->orWhere('demolive_questions.id',$request->search_term)
				->orWhere('demolive_questions.banner_title','like','%'.$request->search_term.'%')
				->orWhere('demolive_questions.writeanswer','like','%'.$request->search_term.'%');
			}
			$data['questions'] = $query->orderBy($request->order_by,$request->sort)->paginate($request->limit);
		return view('admin.demolive_list_pagination ',$data); 
		}
	}
}