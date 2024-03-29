<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Auth;
use Session;
use DB;
use DateTime;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\Coupon;
use App\User;
use App\Country;
use App\Address;
use App\Order;
use App\OrdersProduct;
use App\Wishlist;
use App\Cart;
use Redirect;
use Illuminate\Support\Facades\Mail;


class ProductsController extends Controller
{
    //admin
    public function addProduct(Request $request){
        if($request->isMethod('post')){ 
            $data=$request->all();
           
            //check if category was put
            if(empty($data['category_id'])){
                return redirect()->back()->with('flash_message_error','Missed subcategory!');    
            }

            //check if product code already exists
            $count_code=Product::where(['product_code'=>$data['product_code']])->count();
            if($count_code > 0){
                return redirect()->back()->with('flash_message_error','Product code already exists. Try to generate another product code!');
            }

            //check if product name already exists
            $count_name=Product::where(['product_name'=>$data['product_name']])->count();
            if($count_name > 0){
                return redirect()->back()->with('flash_message_error','Product name not available!');
            }

            $product= new Product;
            $product->category_id= $data['category_id'];
            $product->product_name= $data['product_name'];
            $product->product_code= $data['product_code'];
           
            if(!empty($data['description'])) {$product->description= $data['description'];}else{$product->description= '';}
            if(!empty($data['product_color'])) {$product->product_color= $data['product_color'];}else{$product->product_color= '';}
            if(!empty($data['width'])) {$product->width= $data['width'];}else{$product->width= '';}
            if(!empty($data['height'])) {$product->height= $data['height'];}else{$product->height= '';}
            if(!empty($data['depth'])) {$product->depth= $data['depth'];}else{$product->depth= '';}
            if(!empty($data['material'])) {$product->material= $data['material'];}else{$product->material= '';}
            if(!empty($data['weight'])) {$product->weight= $data['weight'];}else{$product->weight= '';}
            if(!empty($data['maximum_load_supported'])) {$product->maximum_load_supported= $data['maximum_load_supported'];}else{$product->maximum_load_supported= '';}



            $product->brand = $data['brand'];
            $product->price= $data['price']; 
            // upload image   
            if($request->hasfile('image')){
                $image_tmp= Input::file('image');
                if($image_tmp->isValid()){
                    $extension= $image_tmp->getClientOriginalExtension();
                    $filename= rand(111,99999).'.'.$extension;
                    $large_image_path= 'images/backend_images/products/large/'.$filename;
                    $medium_image_path= 'images/backend_images/products/medium/'.$filename;
                    $small_image_path= 'images/backend_images/products/small/'.$filename;
                        //Resize image code
                        Image::make($image_tmp)->save($large_image_path);
                        Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                        Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                        //store image name in product table
                        $product->image=$filename;
                }
            }
            $product->stock = $data['stock'];

            if(empty($data['status'])){$status = 0;}else{$status = 1;}

            $product->status = $status;
            $product->save();  
            return redirect()->back()->with('flash_message_success','Product successfully added!');        
        }
        //Categories drop down start
        $categories= Category::where(['parent_id'=>0])->get();
        $categories_dropdown= "<option selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories= Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
           } 
        }
        $brands = DB::table('brands')->get();
        //Categories drop down start
        return view('admin.products.add_product')->with(compact('categories_dropdown','brands'));


    }
    //admin
    public function editProduct(Request $request, $id=null){
        if($request->isMethod('post')){
            $data=$request->all();

            //check if product name exists
            $count_name = DB::table('products')->where('product_name', $data['product_name'])->count();
            $current_name = DB::table('products')->where('id', $id)->first();
            $current_name=$current_name->product_name;
            if($count_name > 0 && $data['product_name'] != $current_name)
                return redirect()->back()->with("flash_message_error","Product name not available!");
            
            //check if product code exists
            $count_code = DB::table('products')->where('product_code', $data['product_code'])->count();
            $current_code = DB::table('products')->where('id', $id)->first();
            $current_code=$current_code->product_code;
            if($count_code > 0 && $data['product_code'] != $current_code)
                return redirect()->back()->with("flash_message_error","Product code not available!");
            
            if($request->hasfile('image')){
                $this->deleteProductImage($id);
                $image_tmp= Input::file('image');
                if($image_tmp->isValid()){
                    $extension= $image_tmp->getClientOriginalExtension();
                    $filename= rand(111,99999).'.'.$extension;
                    $large_image_path= 'images/backend_images/products/large/'.$filename;
                    $medium_image_path= 'images/backend_images/products/medium/'.$filename;
                    $small_image_path= 'images/backend_images/products/small/'.$filename;
                    //Resize image code
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }   
            }else{
                $filename = $data['current_image'];
            }
                
            
            if(empty($data['description'])){ $data['description']="";}
            if(empty($data['product_color'])) {$data['product_color']="";}
            if(empty($data['width'])) {$data['width']="";}
            if(empty($data['height'])) {$data['height']="";}
            if(empty($data['depth'])) {$data['depth']="";}
            if(empty($data['material'])) {$data['material']="";}
            if(empty($data['weight'])) {$data['weight']="";}
            if(empty($data['maximum_load_supported'])) {$data['maximum_load_supported']="";}

            if(empty($data['status'])){$status = 0;}else{$status = 1;}

          //check if product code already exists
          $productCount=Product::where(['product_code'=>$data['product_code']])->count();
          if($productCount > 1){
              return redirect()->back()->with('flash_message_error','Product code already exists!');
          } 

          Product::where(['id'=>$id])->update([
            'category_id'=>$data['category_id'],
            'product_name'=>$data['product_name'],
            'product_code'=>$data['product_code'],
            'product_color'=>$data['product_color'],
            'width'=>$data['width'],
            'height'=>$data['height'],
            'depth'=>$data['depth'],
            'material'=>$data['material'],
            'weight'=>$data['weight'],
            'maximum_load_supported'=>$data['maximum_load_supported'],
            'description'=>$data['description'], 
            'brand'=> $data['brand'],
            'price'=>$data['price'],
            'image'=>$filename, 
            'stock'=> $data['stock'], 
            'status'=>$status]);
           return redirect()->back()->with('flash_message_success','Product successfully updated!');        

        }
        $productDetails= Product::where(['id'=>$id])->first();
            //Categories drop down start
        $categories= Category::where(['parent_id'=>0])->get();
        $categories_dropdown= "<option selected disabled>Select</option>";
        foreach($categories as $cat){
            if($cat->id==$productDetails->category_id){
                $selected="selected";
            }else{
                $selected="";
            }
            $categories_dropdown .= "<option value='".$cat->id."'".$selected.">".$cat->name."</option>";
            $sub_categories= Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                if($sub_cat->id==$productDetails->category_id){
                    $selected="selected";
                }else{
                    $selected="";
                }
                $categories_dropdown .= "<option value='".$sub_cat->id."'".$selected." >&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }

        }
        $brands = DB::table('brands')->get();
        return view('admin.products.edit_product')->with(compact('productDetails','categories_dropdown','brands'));
        
    }
    //admin
    public function viewProducts(Request $request){
        $products= Product::orderBy('id','desc')->get();
        
        $products=json_decode(json_encode($products));
        foreach($products as $key=> $val){
            $category_name= Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
         }
        return view('admin.products.view_products')->with(compact('products'));
    }
    //admin
    public function deleteProduct($id=null){
        //unlink image
        //$this->deleteProductImage($id);
        //unlink all alternative images
        $this->deleteAllAltImage($id);
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product successfully deleted!');
    }
    //admin
    public function deleteProductImage($id=null){
        //get product image
        $productImage = Product::where(['id' => $id])->first();
        
        if($productImage->image != '' && $productImage->image != NULL){
            //get image's paths
            $large_image_path = 'images/backend_images/products/large/';
            $medium_image_path = 'images/backend_images/products/medium/';
            $small_image_path = 'images/backend_images/products/small/';

            //delete large image if not exists in directory
            if(file_exists($large_image_path.$productImage->image)){
                unlink($large_image_path.$productImage->image);
            }

            //delete medium image if not exists in directory
            if(file_exists($medium_image_path.$productImage->image)){
                unlink($medium_image_path.$productImage->image);
            }

            //delete small image if not exists in directory
            if(file_exists($small_image_path.$productImage->image)){
                unlink($small_image_path.$productImage->image);
            }

            //delete image from product table
            Product::where(['id'=>$id])->update(['image'=>NULL]);
        }
        return redirect()->back()->with('flash_message_success', 'Product image successfully deleted!');
    }
    //admin
    public function deleteAllAltImage($product_id=null){
        //pget product image
        $productImage = DB::table('products_images')->where(['product_id' => $product_id])->get();
        //echo '<pre>'; print_r($productImage); die;
        //get image's paths
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        foreach($productImage as $image){
            //delete large image if not exists in directory
            if(file_exists($large_image_path.$image->image)){
                unlink($large_image_path.$image->image);
            }

            //delete medium image if not exists in directory
            if(file_exists($medium_image_path.$image->image)){
                unlink($medium_image_path.$image->image);
            }


           //delete small image if not exists in directory
            if(file_exists($small_image_path.$image->image)){
                unlink($small_image_path.$image->image);
            }
            
            //delete image from product table
            ProductsImage::where(['id'=>$image->id])->delete();
        }    
    }
    //admin
    public function deleteAltImage($id=null){
        //get product image
        $productImage = ProductsImage::where(['id' => $id]) -> first();

        //get image's paths
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

       //delete large image if not exists in directory
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        //delete medium image if not exists in directory
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }


       //delete small image if not exists in directory
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

         //delete image from product table
        ProductsImage::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product image successfully deleted!');
    }
    //admin
    public function addImages(Request $request, $id=null){

        if($request->isMethod('post')){
            $data = $request->all();
            if($request->hasFile('image')){
                $files = $request->file('image');
                foreach($files as $file){
                    $image = new ProductsImage;
                    $extension = $file->getClientOriginalExtension();
                    $fileName = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$fileName;
                    $medium_image_path = 'images/backend_images/products/medium/'.$fileName;
                    $small_image_path = 'images/backend_images/products/small/'.$fileName;
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600,600)->save($medium_image_path);
                    Image::make($file)->resize(300,300)->save($small_image_path);
                    $image->image=$fileName;
                    $image->product_id=$data['product_id'];
                    $image->save();
                }
            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success','Product image inserted correctly!'); 
        }
        $productDetails = Product::find($id); 
        $productsImg = ProductsImage::where(['product_id' => $id])->get(); 
        $productsImg = json_decode(json_encode($productsImg));
        //echo"<pre>"; print_r($productsImg); die;
        
        $productsImages = "";
        foreach($productsImg as $img){
            $productsImages .= "<tr>
                <td>".$img->id."</td>
                <td>".$img->product_id."</td>
                <td> <img width='150px' src='/images/backend_images/products/small/$img->image'></td>
                <td>  <a  rel='$img->id' rel1='delete-alt-image'  href='javascript:' class='btn btn-danger btn-mini deleteRecord' title='Delete product image'>Delete</a>
                </td>
            </tr>";
        }

        return view('admin.products.add_images')->with(compact('productDetails','productsImages'));
    }
    
    public function products(Request $request, $url = null){
        
        // $search_product = 0;
        if(empty(Session::get('sorting'))){ Session::put('sorting',"alpha"); }
        if(empty(Session::get('paginate'))){ Session::put('paginate',"9"); }
        $categories = Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();

        if(DB::table('categories')->where('url',$url)->exists()){
            $countCategory = Category::where(['url'=>$url, 'status'=>1])->count();
            if($countCategory==0)
                abort(404);

            $categoryDetails = Category::where(['url'=>$url])->first();
            if($categoryDetails->parent_id == 0){
                //if the URL is the URL of the main category
                $subCategories= Category::where(['parent_id'=>$categoryDetails->id])->get();
                foreach($subCategories as $subcat){
                    $cat_ids[] = $subcat->id;
                }
                
                $productsAll= DB::table('products')->whereIn('products.category_id',$cat_ids)->where('products.status',1)
                    ->join('categories','categories.id','=','products.category_id')
                    ->select('products.*','categories.name as category_name');    
                // if($data['sorting'] ==  "price_asc")$productsAll->orderBy('products.price','asc');
                    

                $brandArray=DB::table('products')->select(DB::raw('brand'))->whereIn('products.category_id',$cat_ids)
                    ->where('products.status',1)->join('categories','categories.id','=','products.category_id')
                    ->select('products.*','categories.name as category_name')->groupBy('brand')->get();
                $breadcrumb= "<a style=\"color:#333 !important;\" href='/'>Home</a> / <a style=\"color:#333 !important;\" href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";
                
            }else{
                //se l'URL è l'URL della sottocategoria
                $brandArray=DB::table('products')->select(DB::raw('brand'))
                    ->where(['products.category_id'=>$categoryDetails->id, 'products.status'=>1])
                    ->join('categories','categories.id','=','products.category_id')
                    ->select('products.*','categories.name as category_name')->groupBy('brand')->get();
                
                $productsAll = DB::table('products')->where(['products.category_id'=>$categoryDetails->id])
                    ->where('products.status',1)->join('categories','categories.id','=','products.category_id')
                    ->select('products.*','categories.name as category_name');//->orderBy('products.id','Desc');
                    //->get()
                    

                $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();
                $breadcrumb= "<a style=\"color:#333 !important;\" href='/'>Home</a> 
                            / <a style=\"color:#333 !important;\" href='".$mainCategory->url."'>".$mainCategory->name."</a> / 
                            <a  style=\"color:#333 !important;\" href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";
            }
        }
            // else{
        //     $search_product = 1;
        //     $productsAll = Product::where(function($query) use($url){
        //         $query->where('product_name','like','%'.$url.'%')
        //                 ->orWhere('product_code','like','%'.$url.'%')
        //                 ->orWhere('description','like','%'.$url.'%')
        //                 ->orWhere('product_color','like','%'.$url.'%')
        //                 ->orWhere('brand','like','%'.$url.'%');
        //     })->join('categories','categories.id','=','products.category_id')->where('products.status',1)->where('categories.status',1)->select('products.*','categories.name as category_name');
        // }

        
        if(!empty($_GET['brand'])){
            $brand_test = explode('-', $_GET['brand']);
            $productsAll = $productsAll->whereIn('brand',$brand_test);
        }
        
        

        //total products for category
        $count_products = $productsAll->count();
        
        if($request->isMethod('post')){
            $data=$request->all();
            if(!empty($data['paginate'])){
                Session::put('paginate', $data['paginate']);
                switch ($data['sorting']) {
                    case "alpha":
                        Session::put('sorting', "alpha");
                        break;
                    case "price_asc":
                        Session::put('sorting', "price_asc");
                        break;
                    case "price_desc":
                        Session::put('sorting', "price_desc");
                        break;
                    case "recent":
                        Session::put('sorting', "recent");
                        break;
                }
            }
        }
        // $data=$request->all();
        // echo'<pre>'; print_r($data); die;
        
        $productsAll = Product::setListingDetails($productsAll);
        $start = 0;
        $end = 0;
        $products_currentpage=$productsAll->count();
        $current_page=$productsAll->currentPage();
        $multiple_pages=$productsAll->hasPages();
        if($count_products != 0){
            $start = 1 + (($current_page - 1) * Session::get('paginate'));
            $end = $products_currentpage +(($current_page - 1) * Session::get('paginate'));
        }
        //if product is in sale add new field "new_price"
        foreach($productsAll as $product){
            if($product->in_sale == 1){
                $new_price = DB::table('products_sales')->where('product_id',$product->id)->first();
                $new_price = $new_price->price;
                $product->new_price = $new_price;
            }
        }
       
        // echo'<pre>'; print_r($productsAll); die;
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        // if($search_product == 1){
        //     return view('products.listing')->with(compact('categories','productsAll','url','userCart','count_products','end','start','search_product'));}
        // else{
            return view('products.listing')->with(compact('categories','categoryDetails','productsAll','url','brandArray','breadcrumb','userCart','count_products','end','start','cmsDetails'));
        // }
    }


    public function filter(Request $request){    
        $data=$request->all();
        //echo'<pre>'; print_r($data); die;
        $brandUrl="";
        $finalUrl="";
        if(!empty($data['brandFilter'])){
            foreach($data['brandFilter'] as $brand){
                if(empty($brandUrl)){
                    $brandUrl="&brand=".$brand;
                }else{
                    $brandUrl.= "-".$brand;
                }
            }
        }
        
        if(!empty($data['url']))
            $finalUrl="products/".$data['url']."?".$brandUrl;
        if(!empty($data['pattern']))
            $finalUrl="search-products?".$data['pattern']."?".$brandUrl;
        if(!empty($data['outlet']))
            $finalUrl="outlet"."?".$brandUrl;
        

        return redirect::to($finalUrl);
    }
    

    public function searchProducts(Request $request){
        if($request->isMethod('get')){
            $data=$request->all();
            
            //  echo'<pre>'; print_r($data); die;
            
            if(empty(Session::get('sorting'))){ Session::put('sorting',"alpha"); }
            if(empty(Session::get('paginate'))){ Session::put('paginate',"9"); }
            
            if(!empty($data['pattern']))
                $pattern = $data['pattern'];
            else{
                if(empty($data['brand'])){
                    for($i=0; $i<1; $i++)
                        $pattern = key($data);
                }else{
                    for($i=0; $i<1; $i++)
                        $pattern = key($data);
                    $pattern = substr($pattern, 0, strlen($pattern)-1);
                }
            }
                

            $productsAll = Product::where(function($query) use($pattern){
                $query->where('product_name','like','%'.$pattern.'%')
                        ->orWhere('product_code','like','%'.$pattern.'%')
                        ->orWhere('description','like','%'.$pattern.'%')
                        ->orWhere('product_color','like','%'.$pattern.'%')
                        ->orWhere('brand','like','%'.$pattern.'%');
            })->join('categories','categories.id','=','products.category_id')->where('products.status',1)->where('categories.status',1)->select('products.*','categories.name as category_name');

            $brandArray= Product::where(function($query) use($pattern){
                $query->where('product_name','like','%'.$pattern.'%')
                        ->orWhere('product_code','like','%'.$pattern.'%')
                        ->orWhere('description','like','%'.$pattern.'%')
                        ->orWhere('product_color','like','%'.$pattern.'%')
                        ->orWhere('brand','like','%'.$pattern.'%');
            })->join('categories','categories.id','=','products.category_id')->where('products.status',1)->where('categories.status',1)->select('products.*','categories.name as category_name')->groupBy('brand')->get();

            if(!empty($_GET['brand'])){
                // echo'<pre>'; print_r($_GET['brand']); die;
                $brand_test = explode('-', $_GET['brand']);
                $productsAll = $productsAll->whereIn('brand',$brand_test);
            }
            //total products found
            $count_products = $productsAll->count();

            if(!empty($data['paginate'])){
                Session::put('paginate', $data['paginate']);
            }

            if(!empty($data['sorting'])){
                switch ($data['sorting']) {
                    case "alpha":
                        Session::put('sorting', "alpha");
                        break;
                    case "price_asc":
                        Session::put('sorting', "price_asc");
                        break;
                    case "price_desc":
                        Session::put('sorting', "price_desc");
                        break;
                    case "recent":
                        Session::put('sorting', "recent");
                        break;
                }
            }
        }
        

        $productsAll = Product::setListingDetails($productsAll);
        $start = 0;
        $end = 0;
        $products_currentpage=$productsAll->count();
        $current_page=$productsAll->currentPage();
        $multiple_pages=$productsAll->hasPages();
        if($count_products != 0){
            $start = 1 + (($current_page - 1) * Session::get('paginate'));
            $end = $products_currentpage +(($current_page - 1) * Session::get('paginate'));
        }
        //if product is in sale add new field "new_price"
        foreach($productsAll as $product){
            if($product->in_sale == 1){
                $new_price = DB::table('products_sales')->where('product_id',$product->id)->first();
                $new_price = $new_price->price;
                $product->new_price = $new_price;
            }
        }
        if(!empty($data['pattern']))
            $productsAll->withPath('?'.$data['pattern']);
        else
            $productsAll->withPath('?'.$pattern);
        
        
        
        $userCart = Cart::getProductsCart();
        $categories = Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        
        return view('products.listing_search')->with(compact('categories','productsAll','pattern','userCart','count_products','end','start','brandArray','cmsDetails'));

    }

    public function outlet(Request $request){
        $outlet = 1;
        if($request->isMethod('get')){
            $data=$request->all();
            
            //  echo'<pre>'; print_r($data); die;
            
            if(empty(Session::get('sorting'))){ Session::put('sorting',"alpha"); }
            if(empty(Session::get('paginate'))){ Session::put('paginate',"9"); }
                

            $productsAll = DB::table('products')
                ->where('products.in_sale',1)
                ->join('categories','categories.id','=','products.category_id')
                ->where('products.status',1)
                ->where('categories.status',1)
                ->select('products.*','categories.name as category_name');
               
            
            $brandArray = DB::table('products')
                ->where('in_sale',1)
                ->join('categories','categories.id','=','products.category_id')
                ->where('products.status',1)
                ->where('categories.status',1)
                ->select('products.*','categories.name as category_name')
                ->groupBy('brand')
                ->get();

            if(!empty($_GET['brand'])){
                // echo'<pre>'; print_r($_GET['brand']); die;
                $brand_test = explode('-', $_GET['brand']);
                $productsAll = $productsAll->whereIn('brand',$brand_test);
            }
            //total products found
            $count_products = $productsAll->count();

            if(!empty($data['paginate'])){
                Session::put('paginate', $data['paginate']);
            }

            if(!empty($data['sorting'])){
                switch ($data['sorting']) {
                    case "alpha":
                        Session::put('sorting', "alpha");
                        break;
                    case "price_asc":
                        Session::put('sorting', "price_asc");
                        break;
                    case "price_desc":
                        Session::put('sorting', "price_desc");
                        break;
                    case "recent":
                        Session::put('sorting', "recent");
                        break;
                }
            }
        }
        

        $productsAll = Product::setListingDetails($productsAll);
        $start = 0;
        $end = 0;
        $products_currentpage=$productsAll->count();
        $current_page=$productsAll->currentPage();
        $multiple_pages=$productsAll->hasPages();
        if($count_products != 0){
            $start = 1 + (($current_page - 1) * Session::get('paginate'));
            $end = $products_currentpage +(($current_page - 1) * Session::get('paginate'));
        }
        //if product is in sale add new field "new_price"
        foreach($productsAll as $product){
            if($product->in_sale == 1){
                $new_price = DB::table('products_sales')->where('product_id',$product->id)->first();
                $new_price = $new_price->price;
                $product->new_price = $new_price;
            }
        }
        
        $userCart = Cart::getProductsCart();
        $categories = Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        
        return view('products.listing_outlet')->with(compact('categories','productsAll','userCart','count_products','end','start','brandArray','outlet','cmsDetails'));
    
    }

    public function product($id = null){
        //404 se il prodotto è disabilitato
        $productsCount = Product::where(['id'=>$id, 'status'=>1])->count();
        if($productsCount == 0)
            abort(404);
        
        
        //get product detail
        $productDetails = Product::where('id',$id)->first();
        
        if($productDetails->in_sale == 1){
            $new_price = DB::table('products_sales')->where('product_id',$productDetails->id)->first();
            $new_price = $new_price->price;
            $productDetails->new_price = $new_price;
        }
       

        $relatedProducts = DB::table('products')
            ->where('products.id','!=',$id)
            ->where(['category_id'=>$productDetails->category_id, 'products.status' =>1])
            ->join('categories','categories.id','=','products.category_id')
            ->select('categories.name as category_name','products.*')
            ->get();

        foreach($relatedProducts as $product){
            if($product->in_sale == 1){
                $new_price = DB::table('products_sales')->where('product_id',$product->id)->first();
                $new_price = $new_price->price;
                $product->new_price = $new_price;
            }
        }
        //  echo'<pre>'; print_r($relatedProducts); die;

        
        
        $categoryDetails = Category::where('id',$productDetails->category_id)->first();
        if($categoryDetails->parent_id == 0){
            $breadcrumb= "<li><a style=\"color:#333 !important;\" href='/'>Home</a></li>  <li><a style=\"color:#333 !important;\" href='".$categoryDetails->url."'>".$categoryDetails->name."</a></li> / <li><a style=\"color:#333 !important;\" > ".$productDetails->product_name."</a></li>";
        }else{
            $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();
            $breadcrumb= "<li><a style=\"color:#333 !important;\" href='/'>Home</a></li>  <li><a  style=\"color:#333 !important;\" href='/products/".$mainCategory->url."'>".$mainCategory->name."</a></li>  <li><a style=\"color:#333 !important;\" href='/products/".$categoryDetails->url."'>".$categoryDetails->name."</a></li>  <li><a style=\"color:#333 !important;\" >".$productDetails->product_name."</a></li>";
        }
        //get product alternate image
        $productAltImages = ProductsImage::where('product_id',$id)->get();

        $ratingAvg = DB::table('reviews')
                ->where('product_id', $productDetails->id)
                ->avg('rating');

        $ratingAvg=round($ratingAvg, 1);

        $countReviews = DB::table('reviews')
            ->where('product_id', $productDetails->id)
            ->count();
        
        $userCart = Cart::getProductsCart();
        $categories= Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();
        $cmsDetails = DB::table('cms')->where('id',1)->first();

        return view('products.detail')->with(compact('productDetails','categories','productAltImages','relatedProducts','breadcrumb','ratingAvg','countReviews','userCart','categories','categoryDetails','cmsDetails'));
    }

    public function addtocart(Request $request){
        // Session::forget('coupon_amount');
        // Session::forget('coupon_code');
        // Session::forget('coupon_type');
        if($request->isMethod('post')){
            $data = $request->all();
            // echo'<pre>'; print_r($data); die;
            $getProductsStock=DB::table('products')->where('id',$data['product_id'])->first();
            
            if($getProductsStock->stock < $data['quantity']){
                return redirect()->back()->with('flash_message_error','Requested product quantity not available!');
            }

            if(Auth::check()){ $user_id=Auth::user()->id;}else{ $user_id = NULL; }

            $session_id = Session::get('session_id');
        
            if(Auth::check()){
                $cartDetails=DB::table('cart')->where(['user_id'=> $user_id])->first();
            }else{
                //if user not logged haven't a cart create it
                $cartCount=DB::table('cart')->where(['session_id'=> $session_id])->count();
                if($cartCount == 0){
                    DB::table('cart')->insert([
                        'user_id'=> $user_id,
                        'session_id'=> $session_id,
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                }
                $cartDetails=DB::table('cart')->where(['session_id'=> $session_id])->first();
            }

            $cart_id = $cartDetails->id;
            $countProducts= DB::table('products_carts')->where(['cart_id'=>$cart_id,'product_id'=>$data['product_id']])->count();
        

            if($countProducts > 0){
                return redirect()->back()->with('flash_message_error','Product already in the cart. To change the quantity go to the cart!');
            }else{
                DB::table('products_carts')->insert([
                    'cart_id'=>$cart_id,
                    'product_id'=> $data['product_id'],
                    'product_quantity'=> $data ['quantity']
                ]);
            }
            return redirect()->back()->with('flash_message_success','Product added to cart!');
        }
    }

    public function cart(){
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        return view('products.cart')->with(compact('userCart','cmsDetails'));
    }

    public function deleteCartProduct($id=null){
        // Session::forget('coupon_amount');
        // Session::forget('coupon_code');
        // Session::forget('coupon_type');
        if(Auth::check()){
            $user_id=Auth::user()->id;
            $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
        }else{
            $session_id = Session::get('session_id');
            $cartDetails=DB::table('cart')->where(['session_id'=>$session_id])->first();
        }
        $cart_id=$cartDetails->id;
        DB::table('products_carts')->where(['product_id'=> $id, 'cart_id'=>$cart_id])->delete();
        return redirect('cart')->with('flash_message_success','Product removed from the cart!');
    }

    public function updateCartQuantity($id = null, $quantity = null){

        // Session::forget('coupon_amount');
        // Session::forget('coupon_code');
        // Session::forget('coupon_type');
        if(Auth::check()){
            $user_id=Auth::user()->id;
            $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
        }else{
            $session_id = Session::get('session_id');
            $cartDetails=DB::table('cart')->where(['session_id'=>$session_id])->first();
        }
        $cart_id=$cartDetails->id;
        $productCart = DB::table('products_carts')->where(['product_id'=>$id, 'cart_id'=>$cart_id])->first();
        $productDetails = DB::table('products')->where(['id'=>$id])->first();
        $getStock = $productDetails->stock;
        $updated_quantity = $productCart->product_quantity + $quantity;
        if($getStock >= $updated_quantity){
            DB::table('products_carts')->where(['product_id'=>$id, 'cart_id'=>$cart_id])->increment('product_quantity',$quantity);
            return redirect('cart')->with('flash_message_success','Product quantity updated successfully!');
        }else{
            return redirect('cart')->with('flash_message_error','Requested product quantity not available!');
        }
        
    }

    public function applyCoupon(Request $request){

        Session::forget('coupon_amount');
        Session::forget('coupon_code');
       

        $data = $request->all();
        $couponCount = Coupon::where('coupon_code', $data['coupon_code'])->count();
        if($couponCount == 0){
            return redirect()->back()->with('flash_message_error','Coupon does not exists!');
        }else{
            //perform additional checks
            $couponDetails = Coupon::where('coupon_code', $data['coupon_code'])->first();
            
            //check if coupon is active
            if($couponDetails->status == 0){
                return redirect()->back()->with('flash_message_error','This coupon is not active!');
            }
            if($couponDetails->used == 1){
                return redirect()->back()->with('flash_message_error','This coupon has already been used!');
            }

            //check expiry date
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if($expiry_date < $current_date){
                return redirect()->back()->with('flash_message_error','This coupon has expired!');
            }
            //Coupon is valid


            if(Auth::check()){
                $user_id=Auth::user()->id;
                $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
            }else{
                $session_id = Session::get('session_id');
                $cartDetails=DB::table('cart')->where(['session_id'=>$session_id])->first();
            }
            
            $cart_id=$cartDetails->id;
            $userCart = DB::table('products_carts')->where(['cart_id'=>$cart_id])
                ->join('products', 'products.id', '=', 'products_carts.product_id')
                ->select('products.*','products_carts.cart_id','products_carts.product_quantity')
                ->get();

            $total_amount = 0;

            foreach($userCart as $item){
                if($item->in_sale == 1){
                    $new_price = DB::table('products_sales')->where('product_id',$item->id)->first();
                    $new_price = $new_price->price;
                    $item->new_price = $new_price;
                    $total_amount = $total_amount + ($item->new_price * $item->product_quantity);
                }else{
                    $total_amount = $total_amount + ($item->price * $item->product_quantity);
                }
                
            }

            $couponAmount = $couponDetails->amount;
            $couponAmount = round($couponAmount, 2);

            Session::put('coupon_amount',$couponAmount);
            Session::put('coupon_code',$data['coupon_code']);

            return redirect()->back()->with('flash_message_success','Coupon code applied successfully!');

        }
    }

    public function forgetCoupon(){
        Session::forget('coupon_amount');
        Session::forget('coupon_code');

        return redirect()->action('ProductsController@cart');
    }

    public function checkout(Request $request){
        $user_id=Auth::user()->id;
        $userDetails=User::find($user_id);
        $countries=Country::get();
        $bill_address = DB::table('addresses')->where(['user_id'=>$user_id, 'is_billing'=>1])->first();
        // echo'<pre>'; print_r($bill_address); die;
        $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
        $cart_id=$cartDetails->id;

        //check if there are any products in cart
        $countCartProducts = DB::table('products_carts')->where(['cart_id'=>$cart_id])->count();
        if($countCartProducts == 0){
            return redirect()->back()->with('flash_message_error','There are no products in the cart!');
        }
       
        $userCart = Cart::getProductsCart();
        $categories= Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();

        //check if shipping address exists
        $shippingCount = Address::where(['user_id'=>$user_id, 'is_shipping'=>1])->count();

        //if user account is not updated
        if(empty($bill_address->country) || empty($bill_address->province) || empty($bill_address->city) || 
        empty($bill_address->address) || empty($bill_address->pincode) || empty($bill_address->mobile) ){
            return view('users.account_informations')->with(compact('userDetails','countries','bill_address','userCart','categories'));
        }

        if($request->isMethod('post')){
            $data=$request->all();
            
            
            //return checkout page if any field is empty
            if( empty($data['shipping_name']) || empty($data['shipping_surname']) || empty($data['shipping_country']) || 
            empty($data['shipping_province']) || empty($data['shipping_city']) || empty($data['shipping_address']) || 
            empty($data['shipping_pincode']) || empty($data['shipping_mobile']) ){
                    return redirect()->back()->with('flash_message_error','Please fill in all fields!');
            }

            if($shippingCount > 0){
                //update shipping address
                Address::where(['user_id'=>$user_id,'is_shipping'=>1])->update(['user_name'=>$data['shipping_name'],'user_surname'=>$data['shipping_surname'],
                'country'=>$data['shipping_country'],'province'=>$data['shipping_province'],'city'=>$data['shipping_city'],
                'address'=>$data['shipping_address'],'pincode'=>$data['shipping_pincode'],'mobile'=>$data['shipping_mobile'] ]);
            }else{
                $shippingDetails = new Address;
                $shippingDetails->user_id = $user_id;
                $shippingDetails->is_shipping = 1;
                $shippingDetails->user_name=$data['shipping_name'];
                $shippingDetails->user_surname=$data['shipping_surname'];
                $shippingDetails->country=$data['shipping_country'];
                $shippingDetails->province=$data['shipping_province'];
                $shippingDetails->city=$data['shipping_city'];
                $shippingDetails->address=$data['shipping_address'];
                $shippingDetails->pincode=$data['shipping_pincode'];
                $shippingDetails->mobile=$data['shipping_mobile'];
                $shippingDetails->save();
            }
            return redirect()->action('ProductsController@orderReview');
        }
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        if($shippingCount > 0){
            $shippingDetails = Address::where(['user_id'=>$user_id, 'is_shipping'=>1])->first();
            return view('products.checkout')->with(compact('userDetails','countries','shippingCount','shippingDetails','bill_address','userCart','cmsDetails'));
        }
       
        return view('products.checkout')->with(compact('userDetails','countries','shippingCount','bill_address','userCart','cmsDetails'));
       
    }

    public function orderReview(){
        $user_id=Auth::user()->id;
        $user_email=Auth::user()->email;
        $userDetails = User::where('id', $user_id)->first();
        $shippingDetails = Address::where(['user_id'=>$user_id,'is_shipping'=>1])->first();
        $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
        $cart_id=$cartDetails->id;
        
        $countProduct = DB::table('products_carts')->where(['cart_id'=>$cart_id])->count();
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        $shippingCharges = DB::table('shipping_charges')->where('id',1)->first();
        return view('products.order_review')->with(compact('userDetails','shippingDetails','userCart','countProduct','userCart','cmsDetails','shippingCharges'));
    }

    public function placeOrder(Request $request){
        
        if($request->isMethod('post')){
            $data=$request->all();
            $user_id=Auth::user()->id;

            $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
            $cart_id=$cartDetails->id;
            $countProduct = DB::table('products_carts')->where(['cart_id'=>$cart_id])->count();

            if($countProduct == 0){
                return redirect()->action('ProductsController@cart');
            }

            //check if product is sold out
            $productsCart = DB::table('products_carts')->where('cart_id',$cart_id)->get();
            foreach($productsCart as $product){
                $get_stock = DB::table('products')->where('id', $product->product_id)->pluck('stock')->first();
                $new_stock = $get_stock - $product->product_quantity;
                if($new_stock < 0)
                    return redirect('/cart')->with('flash_message_error','Quantity of product requested is not available. Please update quantity product!');
            }
            

            //check if product is disabled
            foreach($productsCart as $product){
                $get_status = DB::table('products')->where('id', $product->product_id)->pluck('status')->first();
                if($get_status == 0){
                    DB::table('products_carts')->where(['cart_id'=>$cart_id, 'product_id'=>$product->product_id])->delete();
                    return redirect('/cart')->with('flash_message_error','Product requested is not available. Sorry, it will be available soon!');
                }
            }

            //check if category is disabled
            foreach($productsCart as $product){
                $get_category = DB::table('products')->where('id', $product->product_id)->pluck('category_id')->first();
                $get_category_status = DB::table('categories')->where('id', $get_category)->pluck('status')->first();
                if($get_category_status == 0){
                    DB::table('products_carts')->where(['cart_id'=>$cart_id, 'product_id'=>$product->product_id])->delete();
                    return redirect('/cart')->with('flash_message_error','A product category is not currently available. Sorry, try again soon!');
                }
            }

            if(empty(Session::get('coupon_code'))){
                $coupon_code= NULL;
                $coupon_type= NULL;
            }else{
                $coupon_code=Session::get('coupon_code');
                $couponDetails = Coupon::where('coupon_code',$coupon_code)->first();
            }

            if(empty(Session::get('shipping_charges')))
                $shipping_charges = 0;
            else
                $shipping_charges = Session::get('shipping_charges');
                
                
            
            //get shipping address of user
            $shippingDetails = Address::where(['user_id'=>$user_id,'is_shipping'=>1])->first();
            $order = new Order;
            $order->user_id =$user_id;
            $order->address_id =$shippingDetails->id;
            $order->shipping_charges=0;    
            if(empty(Session::get('coupon_code'))){
                $order->coupon_id=null;
            }else{
                $order->coupon_id=$couponDetails->id;
            }
            $order->shipping_charges=$shipping_charges; 
            $order->order_status="New"; 
            $order->payment_method=$data['payment_method']; 
            $order->grand_total=$data['grand_total']; 
            $order->save();

            $order_id=DB::getPdo()->lastInsertId();
            $cartProducts = DB::table('products_carts')->where(['cart_id'=>$cart_id])->get();
            
            // echo'<pre>'; print_r($cartProducts); die;
            foreach($cartProducts as $pro){
                $proDetails = DB::table('products')->where('id',$pro->product_id)->first();
                $catDetails = DB::table('categories')->where('id',$proDetails->category_id)->first();

                $cartPro = new OrdersProduct;
                $cartPro->order_id=$order_id;
                $cartPro->product_id=$pro->product_id;
                $cartPro->product_quantity=$pro->product_quantity;
                //check if product is in sale
                if(DB::table('products')->where(['id'=>$pro->product_id,'in_sale'=>1])->exists()){
                    $product_price = DB::table('products_sales')->where(['product_id'=>$pro->product_id])->first();
                }else{
                    $product_price = DB::table('products')->where(['id'=>$pro->product_id])->first();
                }
                $cartPro->product_price = $product_price->price;
                $cartPro->product_name = $proDetails->product_name;
                $cartPro->product_code = $proDetails->product_code;
                $cartPro->product_color = $proDetails->product_color;
                $cartPro->product_height = $proDetails->height;
                $cartPro->product_width  = $proDetails->width;
                $cartPro->product_depth = $proDetails->depth;
                $cartPro->product_weight = $proDetails->weight;
                $cartPro->product_max_load = $proDetails->maximum_load_supported;
                $cartPro->product_material = $proDetails->material;
                $cartPro->product_brand = $proDetails->brand;
                $cartPro->product_description = $proDetails->description;
                $cartPro->product_image = $proDetails->image;
                $cartPro->category_id = $catDetails->id;
                $cartPro->category_name = $catDetails->name;


                $couponAmount = 0;
                if(!empty(Session::get('coupon_amount')))
                    $couponAmount = Session::get('coupon_amount');
                
                $cartPro->coupon_amount = $couponAmount;
                $cartPro->save();
            }

            Session::put('order_id',$order_id);
            Session::put('grand_total',$data['grand_total']);
            
           
            if($data['payment_method']=="COD"){
                //send confirm order email
                $billingDetails = Address::where(['user_id'=>$user_id,'is_billing'=>1])->first();
                //Order email
                $orderDetails=Order::find($order_id);
                $productDetails = DB::table('orders_products')->where(['order_id'=>$order_id])
                    ->join('products', 'products.id', '=', 'orders_products.product_id')
                    ->select('products.*','orders_products.product_quantity', 'orders_products.product_price')
                    ->get();

                // foreach($productDetails as $product){
                //     if($product->in_sale == 1){
                //         $new_price = DB::table('products_sales')->where('product_id',$product->id)->first();
                //         $new_price = $new_price->price;
                //         $product->new_price = $new_price;
                //     }
                // }
    
                $coupon_id = $orderDetails->coupon_id;
                $couponDetails= Coupon::find($coupon_id);
                $cmsDetails = DB::table('cms')->where('id',1)->first();
                
                $user_email=Auth::user()->email;
                $messageData = [
                    'email' => $user_email,
                    'name' =>$shippingDetails->user_name,
                    'order_id' =>$order_id,
                    'orderDetails' =>$orderDetails,
                    'productDetails' => $productDetails, 
                    'shippingDetails' => $shippingDetails,
                    'billingDetails' =>  $billingDetails,
                    'couponDetails' => $couponDetails,
                    'cmsDetails' =>  $cmsDetails    
                ];
                
                Mail::send('emails.order', $messageData, function($message) use ($user_email){
                    $message->to($user_email)->subject('Order placed - RB-Gym');
                });

                //COD - redirect user to thanks page after saving order
                return redirect('/thanks');
            }
        }
        
        $user_id=Auth::user()->id;
        $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
        $cart_id=$cartDetails->id;
        $countProduct = DB::table('products_carts')->where(['cart_id'=>$cart_id])->count();
        //if no product is in the cart redirect to ProductsController@cart
        if($countProduct == 0){
            return redirect()->action('ProductsController@cart');
        }
    }

    public function thanks(){
        $user_id=Auth::user()->id;

        //reduce stock product
        $cartDetails=DB::table('cart')->where(['user_id'=>$user_id])->first();
        $cart_id=$cartDetails->id;
        Product::updateStock($cart_id);

        //empty cart
        DB::table('products_carts')->where('cart_id',$cart_id)->delete();

        //mark coupon like used
        $coupon_code = Session::get('coupon_code');
        Coupon::where('coupon_code',$coupon_code)->update(['used'=> 1]);

        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        $orderDetails = DB::table('orders')->where('user_id',$user_id)->orderBy('id', 'desc')->first();
        return view('orders.thanks')->with(compact('userCart','cmsDetails','orderDetails'));
    }

    public function userOrders(){
        $user_id=Auth::user()->id;
        $orders=Order::with('orders')->where('user_id',$user_id)->orderBy('id','DESC')->get(); 
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        $categories = Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();
        // $orderDetails = DB::table('orders')->where('orders.user_id',$user_id)->join('orders_products','orders.id','=','orders_products.order_id')->orderBy('orders.id','DESC')->get();
        // echo'<pre>'; print_r($orders); die;
        return view('orders.user_orders')->with(compact('orders','userCart','categories','cmsDetails'));
    }

    public function productOrdered(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            // echo'<pre>'; print_r($data); die;
            $productDetails = DB::table('orders_products')->where(['product_id'=>$data['product_id'], 'order_id'=>$data['order_id']])->first();
            $userCart = Cart::getProductsCart();
            $cmsDetails = DB::table('cms')->where('id',1)->first();
            $categories = Category::with('categories')->where(['parent_id'=>0,'status'=>1])->get();
            return view('orders.detail_product_ordered')->with(compact('userCart','categories','cmsDetails','productDetails'));
        }
    }

    //da eliminare se si lascia così my orders
    public function userOrderDetails($order_id){
        $user_id=Auth::user()->id;
        $orderDetails=Order::with('orders')->where('id',$order_id)->first();
        $productsOrder = DB::table('orders_products')->where(['order_id'=>$order_id])
            ->join('products', 'products.id', '=', 'orders_products.product_id')
            ->select('products.*','orders_products.product_quantity','orders_products.product_price')
            ->get();
        echo'<pre>'; print_r($productsOrder); die;
        
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        return view('orders.user_order_details')->with(compact('productsOrder','orderDetails','userCart','cmsDetails'));

    }

    public function addReview(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            $user_id=Auth::user()->id;

            //if the user has not bought the product fail
            $countProduct = DB::table('orders')->where(['user_id'=>$user_id]) 
                ->join('orders_products', 'orders_products.order_id', '=', 'orders.id')
                ->where('orders_products.product_id', $data['product_id1'])
                ->count();
            if($countProduct == 0)
                return redirect()->back()->with('flash_message_error','You cannot review the product because you have not purchased it!'); 



            //if there is not a raiting fail
            if($data['review_rating'] < 1 || $data['review_rating'] > 5)
                return redirect()->back()->with('flash_message_error','Please enter a rating for your review!'); 

            
            DB::table('reviews')->insert([
                'rating'=>$data['review_rating'],
                'title'=>$data['review_title'],
                'description'=>$data['review_description'],
                'user_id'=>$user_id,
                'product_id'=>$data['product_id1']
            ]);
            return redirect()->back()->with('flash_message_success','Review entered successfully!');     
        }

        //return view('reviews.add_review')->with(compact('productDetails'));
    }

    //admin
    public function viewOrders(){
        $usersOrders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select('orders.*','users.name','users.surname')->orderByDesc('orders.id')
            ->get();
        return view('admin.orders.view_orders')->with(compact('usersOrders'));
    }
    //admin
    public function viewOrderDetails($order_id){
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $user_id = $orderDetails->user_id;
        //get billing address
        $billingDetails = Address::where(['user_id'=>$user_id,'is_billing'=>1])->first();
        //get shipping address
        $shippingDetails = Address::where(['user_id'=>$user_id,'is_shipping'=>1])->first();
        //get user details
        $userDetails=User::find($user_id);
        $user_email =$userDetails->email;

        $productsOrder = DB::table('orders_products')->where(['order_id'=>$order_id])
            ->join('products', 'products.id', '=', 'orders_products.product_id')
            ->select('products.*','orders_products.product_quantity','orders_products.product_price')
            ->get();
        
        //check if coupon has benn used
        if(!empty($orderDetails->coupon_id)){
            $coupon_id=$orderDetails->coupon_id;
            $couponDetails= Coupon::find($coupon_id);
            return view('admin.orders.order_details')->with(compact('orderDetails','user_id','user_email','productsOrder','billingDetails','shippingDetails','couponDetails'));
        }else{
            return view('admin.orders.order_details')->with(compact('orderDetails','user_id','user_email','productsOrder','billingDetails','shippingDetails'));
        }
        
        
    }
    //admin
    public function updateOrderStatus(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            Order::where('id',$data['order_id'])->update(['order_status'=>$data['order_status']]);
            return redirect()->back()->with('flash_message_success','Order status successfully updated!');
        }
    }

    public function wishlist(){
        $session_id = Session::get('session_id');

        if(Auth::check()){
            $user_id=Auth::user()->id;
            $wishDetails = DB::table('wishlists')->where(['user_id'=>$user_id])->first();
            $wish_id = $wishDetails->id;
            $product_count = DB::table('wish_products')->where(['wish_id'=>$wish_id])->count();
        }else{
            $user_id = NULL;
            //if user not logged haven't a wishlist create it
            $wishCount=DB::table('wishlists')->where(['session_id'=> $session_id])->count();
            if($wishCount == 0){
                DB::table('wishlists')->insert([
                    'user_id'=> $user_id,
                    'session_id'=> $session_id
                ]);
            }
            $wishDetails = DB::table('wishlists')->where(['session_id'=>$session_id])->first();
            $wish_id = $wishDetails->id;
            $product_count = DB::table('wish_products')->where(['wish_id'=>$wish_id])->count();
        }
        //check if wishlist contains products
        /*if($product_count == 0){
            return redirect()->back()->with('flash_message_error','La wishlist non contiene prodotti!');
        }*/
    
        $productsWish = DB::table('wish_products')->where(['wish_id'=>$wish_id])
            ->join('products', 'products.id', '=', 'wish_products.product_id')
            ->select('products.*')
            ->get();
        
        //if product is in sale add new field "new_price"
        foreach($productsWish as $product){
            if($product->in_sale == 1){
                $new_price = DB::table('products_sales')->where('product_id',$product->id)->first();
                $new_price = $new_price->price;
                $product->new_price = $new_price;
            }
        }
    
        $categories = Category::with('categories')->where(['parent_id'=>0, 'status'=>1])->get();
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        return view('products.wishlist')->with(compact('productsWish','categories','userCart','cmsDetails')); 
    }

    public function addWishlist($product_id){
        //check if product is already in wishlist
        if(Product::checkIfWished($product_id))
            return redirect()->back()->with('flash_message_error','Product already present in the wishlist!');

        $session_id = Session::get('session_id');
        if(Auth::check()){
            $user_id=Auth::user()->id;
            $wishDetails = DB::table('wishlists')->where(['user_id'=>$user_id])->first();
        }else{
            $user_id=NULL;
            //if user not logged haven't a wishlist create it
            //echo $session_id; die;
            $wishCount=DB::table('wishlists')->where('session_id',$session_id)->count();
           // echo $wishCount;die;
            if($wishCount == 0){
                DB::table('wishlists')->insert([
                    'user_id'=> $user_id,
                    'session_id'=> $session_id
                ]);
            }
            $wishDetails = DB::table('wishlists')->where(['session_id'=>$session_id])->first();
        }
    
        $wish_id = $wishDetails->id;
        DB::table('wish_products')->insert([
            'wish_id'=>$wish_id,
            'product_id'=> $product_id
        ]);
        return redirect()->back()->with('flash_message_success','Product added to the wishlist!');
    }

    public function removeWishlist($product_id){
        //check if product is not in wishlist
        if(Product::checkIfNotWished($product_id))
            return redirect()->back()->with('flash_message_error','Product not present in the wishlist!');

        $session_id = Session::get('session_id');
        if(Auth::check()){
            $user_id=Auth::user()->id;
            $wishDetails = DB::table('wishlists')->where(['user_id'=>$user_id])->first();
        }else{
            $user_id=NULL;
            $wishDetails = DB::table('wishlists')->where(['session_id'=>$session_id])->first();
        }
        
        $wish_id = $wishDetails->id;
        DB::table('wish_products')->where(['wish_id'=>$wish_id, 'product_id'=>$product_id])->delete();
        return redirect()->back()->with('flash_message_success','Product removed from the wishlist!');
    }

    //admin
    //return all reviews to admin page
    public function viewReviews(){
        $reviewsDetails = DB::table('reviews')
            ->join('users','users.id','=','reviews.user_id')
            ->join('products','products.id','=','reviews.product_id')
            ->select('reviews.*','users.email','products.product_name')
            ->orderBy('id','desc')
            ->get();
        
       // echo '<pre>'; print_r($reviewsDetails); die;
        return view('admin.reviews.view_reviews')->with(compact('reviewsDetails')); 
    }
    
    public function contactUs(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            DB::table('contact_us')->insert([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'subject'=>$data['subject'],
                'message'=>$data['message'],
                'resolved'=>0,
            ]);
            return redirect()->back()->with('flash_message_success','Request successfully sent. We will reply as soon as possible!');
        }
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        return view('help.contact_us')->with(compact('userCart','cmsDetails'));
    }

    public function faq(){
        $userCart = Cart::getProductsCart();
        $cmsDetails = DB::table('cms')->where('id',1)->first();
        $faqs = DB::table('faqs')->where('status',1)->select('faqs.question','faqs.answer')->get();
        return view('help.faq')->with(compact('userCart','faqs','cmsDetails'));
    }

}






