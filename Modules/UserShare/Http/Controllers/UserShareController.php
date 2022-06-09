<?php

namespace Modules\UserShare\Http\Controllers;

use App\BillingSet;
use App\OtGroupSubCategory;
use App\ServiceCost;
use App\Services\UserService;
use App\TaxGroup;
use App\User;
use App\UserShare;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\View\View;
use App\Utils\Helpers;

class UserShareController extends Controller {
	protected $page_limit = 50;
	public $error_message = 0;

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index() {
		$data['doctors']        = UserService::getDoctors( [ 'id', 'firstname', 'middlename', 'lastname' ] );
		$data['billing_types']  = BillingSet::all();
		$data['categories']     = config( 'usershare.categories' );
		$data['sub_categories'] = OtGroupSubCategory::all();
		$data['tax_groups']     = TaxGroup::all();
		$data['user_shares']    = UserShare::with( 'user', 'sub_category' )->paginate( $this->page_limit );

		$data['user_shares']->setPath( route( 'usershare.filter' ) );


		return view( 'usershare::index', $data );
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create() {
		return view( 'usershare::create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store( Request $request ) {
		$request->validate( [
			'billing_set'  => 'required',
			'doctor_id'    => 'required',
			'item_type'    => 'required',
			'item_name'    => 'required',
			'category'     => 'required',
			'tax_group_id' => 'required',
			'share'        => 'required',
			// 'ot_group_sub_category_id' => 'required',
		] );

		$shares_combinations = $this->getSharesCombination(
			array(
				array( $request->doctor_id ),
				$request->item_name,
				$request->item_type,
				array( $request->category ),
				$request->billing_set
			)
		);
		foreach ( $shares_combinations as $combination ) {
			try {
				$userShare = UserShare::where( [
					[ 'flduserid', $request->doctor_id ],
					[ 'flditemname', $combination[1] ],
					[ 'flditemtype', $combination[2] ],
					[ 'category', $request->category ],
					[ 'billing_mode', $combination[4] ],
					[ 'ot_group_sub_category_id', $request->sub_category_id ],
				] )->first();

				if ( $userShare ) {
					Helpers::logStack(["User share already exist in user share create", "Error"]);
					$request->session()->flash( 'success_message', 'Duplicate Entry.' );

					return redirect()->route( 'usershare.index' );
				}

				$share                           = new UserShare;
				$share->flduserid                = $request->doctor_id;
				$share->fldparttime                = isset($request->parttime)?true:false;
				$share->flditemname              = $combination[1];
				$share->flditemtype              = $combination[2];
				$share->category                 = $request->category;
				$share->flditemshare             = $request->share;
				$share->billing_mode             = $combination[4];
				$share->ot_group_sub_category_id = $request->sub_category_id;
				$share->flditemtax               = TaxGroup::find( $request->tax_group_id )->fldtaxper;
				$share->currentuser              = \Auth::guard( 'admin_frontend' )->user()->flduserid;
				$share->updated_at               = date( 'Y-m-d H:i:s' );
				$share->ipdreferal               = $request->ipdreferal ? $request->ipdreferal : 0;
				$share->timestamps               = false;

				$share->save();
				Helpers::logStack(["User share created", "Event"], ['current_data' => $share]);
			} catch ( \Exception $e ) {
				Helpers::logStack([$e->getMessage() . " in user share create", "Error"]);
				$request->session()->flash( 'error_message', $e->getMessage() );

				return redirect()->back();
			}
		}
		$request->session()->flash( 'success_message', 'Added successfully.' );

		return redirect()->route( 'usershare.index' );

	}

	/**
	 * Show the specified resource.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show( $id ) {

		return view( 'usershare::show' );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit( $id ) {
		return view( 'usershare::edit' );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response
	 */
	public function update( Request $request, $id ) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy( $id ) {
		//
	}

	public function getSharesCombination( $arrays, $i = 0 ) {
		if ( ! isset( $arrays[ $i ] ) ) {
			return array();
		}
		if ( $i == count( $arrays ) - 1 ) {
			return $arrays[ $i ];
		}

		// get combinations from subsequent arrays
		$tmp = $this->getSharesCombination( $arrays, $i + 1 );

		$result = array();

		// concat each array from tmp with each element from $arrays[$i]
		foreach ( $arrays[ $i ] as $v ) {
			foreach ( $tmp as $t ) {
				$result[] = is_array( $t ) ?
					array_merge( array( $v ), $t ) :
					array( $v, $t );
			}
		}

		return $result;
	}

	public function getItemTypeFromBillingType( $billing_set ) {
		$billing_sets = explode( ",", $billing_set );
		$data         = (object) [];
		$success      = false;
		$message      = "";
		$status_code  = 200;
		$data         = ServiceCost::where( 'fldgroup', $billing_sets[0] )->select( 'flditemtype' )->groupBy( 'flditemtype' )->get();

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function getItemListFromItemType( $item_type ) {
		$data        = (object) [];
		$success     = false;
		$message     = "";
		$status_code = 200;

		$data = ServiceCost::where( 'flditemtype', $item_type )->select( 'flditemname' )->groupBy( 'flditemname' )->get();

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function getItemListFromCategory( $category, $item_type ) {
		$data        = (object) [];
		$item_types  = explode( ',', $item_type );
		$success     = false;
		$message     = "";
		$status_code = 200;

		$data = ServiceCost::where( [
			[ 'category', 'LIKE', '%' . '"' . $category . '"' . '%' ]
		] )->
		whereIn( 'flditemtype', $item_types )->select( 'flditemname' )->get();

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function filter( Request $request ) {
		$data          = (object) [];
		$success       = true;
		$paginate_view = "";
		$message       = "";
		$status_code   = 200;

		try {
			$query = UserShare::query()->with( 'user' );
			if ( isset( $request->keyword ) ) {
				$keyword = $request->keyword;
				$query->whereHas( 'user', function ( $q ) use ( $keyword ) {
					$q->where( DB::raw( "CONCAT_WS(' ', firstname, middlename, lastname)" ), 'LIKE', '%' . $keyword . '%' );
				} )
				      ->orWhere( 'flditemname', 'LIKE', '%' . $keyword . '%' );
			}
			$data          = $query->paginate( $this->page_limit );
			$paginate_view = View( 'frontend.common.paginate' )->with( [
				'data'  => $data,
				'query' => $request->except( 'page' )
			] )->render();

		} catch ( \Exception $e ) {
            Helpers::logStack([$e->getMessage() . ' in user share filter', "Error"]);
			$success = false;
		}

		return response()->json( [
			'data'          => $data,
			'success'       => $success,
			'paginate_view' => $paginate_view,
			'message'       => $message,
		] );
	}


	public function getDoctorListFromItemName( $item_name ) {
		$data        = (object) [];
		$success     = false;
		$message     = "";
		$status_code = 200;

		$data = UserShare::where( 'flditemname', $item_name )->with( [
			'user' => function ( $q ) {
				$q->select( 'id', 'firstname', 'middlename', 'lastname' );
			}
		] )->whereHas( 'user' )->get();

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function storeSubCategory( Request $request ) {
		$request->validate( [
			'name' => 'unique:ot_group_sub_categories,name'
		] );

		$sub       = new OtGroupSubCategory();
		$sub->name = $request->name;

		$sub->save();

		return response()->json( [
			'data'    => $sub,
			'success' => true,
			'message' => 'Saved succesfully'
		] );
	}

	public function getAllOtGroupList() {
		$groups = OtGroupSubCategory::get();

		return response()->json( [
			'data'    => $groups,
			'success' => true,
			'message' => 'List fetched'
		] );
	}

	public function getDoctorBillingMode( $clone_doctor_id ) {
		$doctor_billing_modes = UserShare::select('billing_mode')->where( [
			[ 'flduserid', $clone_doctor_id ],
		] )->groupby( 'billing_mode' )->get();
		$data                 = (object) [];
		$success              = false;
		$message              = "";
		$status_code          = 200;
		$data                 = $doctor_billing_modes;

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function getDoctorItemType( $clone_doctor_id ,$billing_id) {
		$billing_ids  = explode( ',', $billing_id );
		$doctor_item_types = UserShare::select('flditemtype')->where( [
			[ 'flduserid', $clone_doctor_id ],
		] )->whereIn('billing_mode',$billing_ids)->groupby( 'flditemtype' )->get();
		$data              = (object) [];
		$success           = false;
		$message           = "";
		$status_code       = 200;
		$data              = $doctor_item_types;

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function getDoctorItemList( $clone_doctor_id ,$billing_id, $item_type_id,$category ) {
		$data        = (object) [];
		$billing_ids  = explode( ',', $billing_id );
		$item_type_ids  = explode( ',', $item_type_id );
		$success     = false;
		$message     = "";
		$status_code = 200;
		$data        = UserShare::
		where( 'flduserid', $clone_doctor_id )
		                        ->whereIn('billing_mode',$billing_ids)
		                        ->whereIn( 'flditemtype', $item_type_ids )
		                        ->where('category', $category)
		                        ->select( 'flditemname' )->groupby( 'flditemname' )->get();

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}
	public function getDoctorCategoryList( $clone_doctor_id ,$billing_id, $item_type_id) {
		$billing_ids  = explode( ',', $billing_id );
		$item_type_ids  = explode( ',', $item_type_id );
		$data        = (object) [];
		$success     = false;
		$message     = "";
		$status_code = 200;
		$data        = UserShare::
	where( 'flduserid', $clone_doctor_id )
	->whereIn( 'billing_mode',$billing_ids )
	->whereIn( 'flditemtype', $item_type_ids )
		                        ->select( 'category' )->groupby( 'category' )->get();

		return response()->json( [
			'data'    => $data,
			'success' => $success,
			'message' => $message,
		] );
	}

	public function clone( Request $request ) {
		$request->validate( [
			'clone_doctor_id'  => 'required',
			'doctor_id'    => 'required',
			'item_name'    => 'required',
		] );

		if ( $request->clone_doctor_id == $request->doctor_id ) {
			Helpers::logStack(["User share cannot be cloned in user share clone", "Error"]);
			$request->session()->flash( 'error_message', 'You can\'t clone same doctor.' );
			$this->error_message = 1;
			return redirect()->route( 'usershare.index' );
		}
		$category = $request->category;
		$billing_set = $request->billing_set;
		$item_type = $request->item_type;
		$item_name = $request->item_name;
		$clone_by_categories = UserShare::where( 'flduserid', $request->clone_doctor_id)
									->where('category',$category)
									->whereIn('billing_mode',$billing_set)
									->whereIn('flditemtype',$item_type)
									->when(isset($item_name) && $item_name[0] != 'all', function ($q) use ($item_name) {
										return $q->whereIn('flditemname',$item_name);
									})
									->get();
//		dd($clone_by_categories);
		foreach ($clone_by_categories as $clone_by_type){
			$this->cloneUser($request,$clone_by_type);
		}

		if($this->error_message == 0)
			$request->session()->flash( 'success_message', 'Cloned successfully.' );

		return redirect()->route( 'usershare.index' );

	}

	public function cloneUser($request,$clone_by_type){
		try{
			$userShare = UserShare::where( [
				[ 'flduserid', $request->doctor_id ],
				[ 'flditemname', $clone_by_type->flditemname ],
				[ 'flditemtype', $clone_by_type->flditemtype ],
				[ 'category', $clone_by_type->category ],
				[ 'billing_mode', $clone_by_type->billing_mode ],
				[ 'ot_group_sub_category_id', $clone_by_type->ot_group_sub_category_id ],
			] )->first();

			if ( $userShare ) {
				Helpers::logStack(["User share already exist in user share clone", "Error"]);
				$request->session()->flash( 'error_message', 'Duplicate Entry.' );
				$this->error_message = 1;
				return redirect()->route( 'usershare.index' );
			}

				$userShare = UserShare::create ([
					'flduserid'=>$request->doctor_id,
					'flditemname'=>$clone_by_type->flditemname,
					'flditemtype'=>$clone_by_type->flditemtype,
					'category'=>$clone_by_type->category,
					'flditemshare'=>$clone_by_type->flditemshare,
					'billing_mode'=>$clone_by_type->billing_mode,
					'ot_group_sub_category_id'=>$request->ot_group_sub_category_id,
					'flditemtax'=>$clone_by_type->flditemtax,
					'updated_at'=>date( 'Y-m-d H:i:s' ),
					'ipdreferal'=>$clone_by_type->ipdreferal,
				]);
				Helpers::logStack(["User share cloned", "Event"], ['current_data' => $userShare]);
		}
		catch ( \Exception $e ) {
				Helpers::logStack([$e->getMessage() . " in user share clone", "Error"]);
				$request->session()->flash( 'error_message', $e->getMessage() );

				return redirect()->back();
			}
	}

}
