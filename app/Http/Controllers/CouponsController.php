<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use DB;

class CouponsController extends Controller
{
    //admin
    public function addCoupon(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();

             //check if product code already exists
             $couponCount=Coupon::where(['coupon_code'=>$data['coupon_code']])->count();
             if($couponCount > 0){
                 return redirect()->back()->with('flash_message_error','Coupon code already exists. Try to generate another coupon code!');
             }
            $coupon = new Coupon;
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->expiry_date = $data['expiry_date'];
            $coupon->used = 0;
            if(empty($data['status'])){
                $coupon->status = 0;
            }
            else{
                $coupon->status = $data['status'];
            }
            $coupon->save();
            return redirect()->back()->with('flash_message_success','Coupon successfully added!');
        }
        return view('admin.coupons.add_coupon');
    }

    public function editCoupon(Request $request, $id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo'<pre>'; print_r($data); die;
            //check if coupon code already exists
            $count_code = DB::table('coupons')->where('coupon_code', $data['coupon_code'])->count();
            $current_code = DB::table('coupons')->where('id', $id)->first();
            $current_code=$current_code->coupon_code;
            if($count_code > 0 && $data['coupon_code']!==$current_code)
                return redirect()->back()->with("flash_message_error","Coupon code not available!");

            $coupon = Coupon::find($id);
            $coupon->coupon_code=$data['coupon_code'];
            $coupon->amount=$data['amount'];
            $coupon->expiry_date=$data['expiry_date'];
            if(empty($data['status'])){
                $data['status'] = 0;
            }
            $coupon->status=$data['status'];
            $coupon->save();
            return redirect()->back()->with('flash_message_success','Coupon successfully modified!');
        }       
        $couponDetails = Coupon::find($id);
        return view('admin.coupons.edit_coupon')->with(compact('couponDetails'));

    }

    public function viewCoupons(){
        $coupons = Coupon::orderBy('id','desc')->get();
        return view('admin.coupons.view_coupons')->with(compact('coupons'));
    }


    public function deleteCoupon($id=null){
        Coupon::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Coupon successfully deleted!');
    }
}
