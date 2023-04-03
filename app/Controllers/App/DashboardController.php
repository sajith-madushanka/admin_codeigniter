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
    // function __construct() { 
    //     parent::__construct(); 
         
    //     // Load pagination library 
    //     $this->load->library('ajax_pagination'); 
         
    //     // Load post model 
    //     $this->load->model('post'); 
         
    //     // Per page limit 
    //     $this->perPage = 4; 
    // } 
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
        // return $this->respond('<tr><th>1</th></tr>');
        // Get filter value
        try{
        $filter_name = $this->request->getPost('filter_name');
        
        // Get current page
        $page = $this->request->getPost('page') ?? 0;
        
         $pneumatic_pair = new PneumaticPair();
         $rows =  $pneumatic_pair->countAll();
        // return $this->respond($pneumatic_pair->countAll());
        // Set pagination configuration
        // $config = [
        //     'baseURL' => base_url() . 'get_data',
        //     'totalRows' => $pneumatic_pair->countAll(),
        //     'perPage' => 10,
        //     'uriSegment' => 3,
        //     'current' => $page
        // ];
        //$pager->initialize($config);
        
        // Get filtered and paginated data
        //$data = $this->pneumatic_pair->get_filtered_data($filter_name, $config['perPage'], ($page - 1) * $config['perPage']);
        $data = $pneumatic_pair->paginate(5,'',$page);
        // return $data;
       // return $data;
        // Generate table data
        $table_data = '';
        foreach ($data as $row) {
            $table_data .= '<tr>';
            $table_data .= '<th>' . $row['id'] . '</th>';
            $table_data .= '<td>' . $row['right_rfid'] . '</td>';
            $table_data .= '<td>' . $row['left_rfid'] . '</td>';
            $table_data .= '</tr>';
        }

        // Generate pagination links
       // $pagination = $this->pager->links();

        // Return JSON response
        return $this->response->setJSON([
            'table_data' => $table_data,
            'links'=>$pneumatic_pair->pager->links()
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