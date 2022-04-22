<!-- ########################################################################################## -->
<?php
/*
?>
<section class="one_quarter">
  <h2 class="title">From The Blog</h2>
  <article>
	<header>
	  <h2>Comming soon...</h2>
	  <!--
	  <address>
	  <a href="#">Admin</a>, domainname.com
	  </address>
	  <time datetime="2000-04-06">Friday, 6<sup>th</sup> April 2000</time>
	  -->
	</header>
	<!--
	<p>Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet.</p>
	<footer class="more"><a href="#">Read More &raquo;</a></footer>
	-->
  </article>
</section>
<?php
*/
?>
<section class="one_quarter">
  <h2 class="title">Quick Links</h2>
  <nav>
	<ul>
	  <li><?php echo $this->Html->link('Home', '/');?></li>
	  <li><?php echo $this->Html->link('About us', '/pages/about-us', array('title'=>'About us'));?></li>
	  <li><?php echo $this->Html->link('Our Clients', '/pages/our-clients', array('title'=>'Our Clients'));?></li>          
	  <li><?php echo $this->Html->link('Contact us', '/users/contactus');?></li>
	  <li class='last'><?php echo $this->Html->link('Register', '/users/register', array('title'=>'Register your plant nursery, garden, landscaping and gardening service, etc.'));?></li>
	</ul>
  </nav>
</section>
<section class="three_quarter lastbox" style=" text-align:justify; text-justify:inter-word;">
	<h2 class="title">About US</h2>
	<?php //echo $this->Html->image('images/utilizing_web2.png', array('width'=>'130', 'height'=>'130', 'style'=>'float:left;', 'Utilizing the power of web', 'alt'=>'e nursery powered by web'));?>
  
	<p>We are a small group of Engineers working towards development of Agricultural Sector by utilizing the power of Web and Open Source Technologies.</p>
	<p>
		LetsGreenify, formerly known as eNursery, founded in November 2011, is a web platform which provides Websites or Web Stores for Landscapers, Plant Nurseries and Gardens. It provides an opportunity for Nursery owners to showcase and sell plants over web. For Landscapers it acts as a medium to showcase their work and the services they provide.
	</p>		
  <footer class="more"><?php echo $this->Html->link('Read more', '/pages/about-us');?></footer>
</section>
<!-- ########################################################################################## -->