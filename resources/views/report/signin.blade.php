@extends('frontend.layouts.master') 
<style type="text/css">
	
.glass-box{
  position: relative;
  width: 250px;
  height: 350px;
  top: calc(50% - 175px);
  left: calc(50% - 125px);
  background: inherit;
  border-radius: 2px;
  overflow: hidden;
}

.glass-box:after{
 content: '';
 width: 300px;
 height: 300px;
 background: inherit; 
 position: absolute;
 left: -25px;
 left position
 right: 0;
 top: -25px; 
 bottom: 0;
 box-shadow: inset 0 0 0 200px rgba(255,255,255,0.05);
 filter: blur(10px);
}

</style>
@section('content')
<div class="container">
	<div class="glass-box">
	  <div class="user-login-box">
	    <span class="user-icon"></span>
	    <div class="user-name">
	    	
	    </div>
	    <input class="user-password" type="text" />
	  </div>
	  
	</div>
</div>
@endsection