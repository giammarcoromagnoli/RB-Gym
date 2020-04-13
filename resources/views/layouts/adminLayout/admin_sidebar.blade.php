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

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> </a>
      <ul <?php if(preg_match("/product/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li  <?php if(preg_match("/add-product/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-product')}}">Add product</a></li>
        <li  <?php if(preg_match("/view-products/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-products')}}">View products</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Coupons</span> </a>
      <ul <?php if(preg_match("/coupon/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-coupon/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-coupon')}}">Add coupon</a></li>
        <li <?php if(preg_match("/view-coupons/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-coupons')}}">View coupons</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Orders</span> </a>
      <ul <?php if(preg_match("/Orders/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-orders/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-orders')}}">View orders</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Banners</span> </a>
      <ul <?php if(preg_match("/banner/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/add-banner/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-banner')}}">Add banner</a></li>
        <li <?php if(preg_match("/view-banners/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-banners')}}">View banners</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Users</span> </a>
      <ul <?php if(preg_match("/users/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-users/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-users')}}">View users</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Reviews</span> </a>
      <ul <?php if(preg_match("/reviews/i", $url)) { ?> style="display: block;" <?php } ?>>
        <li <?php if(preg_match("/view-reviews/i", $url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-reviews')}}">View reviews</a></li>
      </ul>
    </li>

  <!--
    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Utenti</span> </a>
      <ul>
        <li><a href="{{url('/admin/add-user')}}">Aggiungi utente</a></li>
        <li><a href="{{url('/admin/view-users')}}">Visualizza utenti</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Gruppi</span> </a>
      <ul>
        <li><a href="{{url('/admin/add-role')}}">Aggiungi gruppo</a></li>
        <li><a href="{{url('/admin/view-roles')}}">Visualizza gruppi</a></li>
      </ul>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Servizi</span> </a>
      <ul>
        <li><a href="{{url('/admin/add-permission')}}">Aggiungi servizio</a></li>
        <li><a href="{{url('/admin/view-permissions')}}">Visualizza servizi</a></li>
      </ul>
    </li>
    
    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Utenti-Gruppi</span> </a>
      <ul>
        <li><a href="{{url('/admin/add-model-has-roles')}}">Assegna gruppo</a></li>
        <li><a href="{{url('/admin/view-models-has-roles')}}">Visualizza assegnazioni</a></li>
      </ul>
    </li>
    -->
    
  </ul>
</div>
<!--sidebar-menu-->
