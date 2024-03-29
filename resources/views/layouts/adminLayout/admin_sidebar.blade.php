<script src="{{asset('js/backend_js/permissions.js')}}"></script>
<?php $url = url()->current(); ?>
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li  <?php if(preg_match("/dashboard/i", $url)) { ?> class="active" <?php } ?> ><a href="{{url('/admin/dashboard')}}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
    
    
    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> </a>
      <ul  <?php if(preg_match("/categor/i", $url)) { ?> style="display: block;" <?php } ?> >
        <li <?php if(preg_match("/add-category/i", $url)) { ?> class="active" <?php } ?> > <a href="{{url('/admin/add-category')}}">Add category</a></li>
        <li <?php if(preg_match("/view-categories/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-categories')}}">View categories</a></li>
     
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-briefcase"></i> <span>Products</span> </a>
      <ul <?php if(preg_match("/product/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li  <?php if(preg_match("/add-product/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-product')}}">Add product</a></li>
        <li  <?php if(preg_match("/view-products/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-products')}}">View products</a></li>
      </ul>
    </li>
    
    <li class="submenu"> <a href="#"><i class="icon icon-star"></i> <span>Brands</span> </a>
      <ul <?php if(preg_match("/brand/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-brand/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-brand')}}">Add brand</a></li>
        <li <?php if(preg_match("/view-brands/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-brands')}}">View brands</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-gift"></i> <span>Coupons</span> </a>
      <ul <?php if(preg_match("/coupon/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-coupon/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-coupon')}}">Add coupon</a></li>
        <li <?php if(preg_match("/view-coupons/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-coupons')}}">View coupons</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-shopping-cart"></i> <span>Orders</span> </a>
      <ul <?php if(preg_match("/Orders/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-orders/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-orders')}}">View orders</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-home"></i> <span>Homepages</span> </a>
      <ul <?php if(preg_match("/homepage/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-homepages/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-homepages')}}">View homepages</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-picture"></i> <span>Banners</span> </a>
      <ul <?php if(preg_match("/banner/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-banner/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-banner')}}">Add banner</a></li>
        <li <?php if(preg_match("/view-banners/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-banners')}}">View banners</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-bullhorn"></i> <span>Sales</span> </a>
      <ul <?php if(preg_match("/sale/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-sale/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-sale')}}">Add sale</a></li>
        <li <?php if(preg_match("/view-sales/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-sales')}}">View sales</a></li>
      </ul>
    </li>
   
    <li class="submenu"> <a href="#"><i class="icon-user"></i> <span>Users</span> </a>
      <ul <?php if(preg_match("/users/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-users/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-users')}}">View users</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-comment-alt"></i> <span>Reviews</span> </a>
      <ul <?php if(preg_match("/reviews/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-reviews/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-reviews')}}">View reviews</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-laptop"></i> <span>Developers</span> </a>
      <ul <?php if(preg_match("/developers/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-developer/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-developer')}}">Add developer</a></li>
        <li <?php if(preg_match("/view-developers/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-developers')}}">View developers</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-group"></i> <span>Groups</span> </a>
      <ul <?php if(preg_match("/groups/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-group/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-group')}}">Add group</a></li>
        <li <?php if(preg_match("/view-groups/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-groups')}}">View groups</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Services</span> </a>
      <ul <?php if(preg_match("/services/i", $url)) { ?> style="display: block;" <?php } ?>>
      <li <?php if(preg_match("/view-services/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-services')}}">View services</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-truck"></i> <span>Shipping charges</span> </a>
      <ul <?php if(preg_match("/shipping_charges/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-shipping_charges/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-shipping-charges')}}">View shipping charges</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-bullhorn"></i> <span>CMS</span> </a>
      <ul <?php if(preg_match("/cms/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-cms/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-cms')}}">View CMS</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-comments"></i> <span>Messages</span> </a>
      <ul <?php if(preg_match("/messages/i", $url)) { ?> style="display: block;" <?php } ?>>
      <li <?php if(preg_match("/view-messages/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-messages')}}">View messsages</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon-question-sign"></i> <span>Faqs</span> </a>
      <ul <?php if(preg_match("/faq/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-faq/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-faq')}}">Add faq</a></li>
        <li <?php if(preg_match("/view-faqs/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-faqs')}}">View faqs</a></li>
      </ul>
    </li>


  </ul>
</div>
<!--sidebar-menu-->
