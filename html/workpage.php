<?php
class WorkPage{

  function __construct()
  {
            add_shortcode( 'pp', array($this,'portfolio_page_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'load_js'));
            add_action( 'wp_enqueue_scripts', array($this,'portfolio_stylesheet') );
          //  add_action('wp_enqueue_scripts', array($this, 'load_isotope_middle_js'));
           // add_action('wp_enqueue_scripts', array($this, 'load_isotope_setting_js'));
            add_action( 'wp_footer', array($this,'load_isotope_middle_js') );
            add_action( 'wp_footer', array($this,'load_isotope_setting_js') );
          
  }


  function portfolio_stylesheet() {
    wp_enqueue_style( 'portfoliostyle', plugins_url( 'css/portfolio.css', dirname(__FILE__) ) );
  }

  function load_js(){


         wp_enqueue_script('jquery');
         wp_enqueue_script('isotope',plugins_url('js/jquery.isotope.min.js', dirname(__FILE__)));

  }
  function load_isotope_middle_js(){  ?>


     <script type="text/javascript">

     		jQuery(function ($) {
    /* You can safely use $ in this code block to reference jQuery */

$.Isotope.prototype._getCenteredMasonryColumns = function() {

    this.width = this.element.width();

    var parentWidth = this.element.parent().width();

    var colW = this.options.masonry && this.options.masonry.columnWidth || // i.e. options.masonry && options.masonry.columnWidth

    this.$filteredAtoms.outerWidth(true) || // or use the size of the first item

    parentWidth; // if there's no items, use size of container

    var cols = Math.floor(parentWidth / colW);

    cols = Math.max(cols, 1);

    this.masonry.cols = cols; // i.e. this.masonry.cols = ....
    this.masonry.columnWidth = colW; // i.e. this.masonry.columnWidth = ...
};

$.Isotope.prototype._masonryReset = function() {

    this.masonry = {}; // layout-specific props
    this._getCenteredMasonryColumns(); // FIXME shouldn't have to call this again

    var i = this.masonry.cols;

    this.masonry.colYs = [];
        while (i--) {
        this.masonry.colYs.push(0);
    }
};

$.Isotope.prototype._masonryResizeChanged = function() {

    var prevColCount = this.masonry.cols;

    this._getCenteredMasonryColumns(); // get updated colCount
    return (this.masonry.cols !== prevColCount);
};

$.Isotope.prototype._masonryGetContainerSize = function() {

    var unusedCols = 0,

    i = this.masonry.cols;
        while (--i) { // count unused columns
        if (this.masonry.colYs[i] !== 0) {
            break;
        }
        unusedCols++;
    }

    return {
        height: Math.max.apply(Math, this.masonry.colYs),
        width: (this.masonry.cols - unusedCols) * this.masonry.columnWidth // fit container to columns that have been used;
    };
};

});


     </script>

  <?php
  }
  



  function load_isotope_setting_js(){  ?>


     <script type="text/javascript">

          jQuery(function ($) {
    /* You can safely use $ in this code block to reference jQuery */

var $work = $('#portfolio');

$work.isotope({
  // options
  itemSelector : '.work',
  resizable: false,
  masonry: {
    columnWidth: 300,
    cornerStampSelector: '.corner-stamp'
  }
  
});

// filter items when filter link is clicked
$('#filters a').click(function(){
  var selector = $(this).attr('data-filter');
  $work.isotope({ filter: selector });
  return false;
});

});


     </script>

  <?php
  }

  function portfolio_page_shortcode() {

    /*
	$output = '<p>
	              Hello World!
	           </p>';

	return $output;
	*/
	
	$this->display_work();
}



function removenontag($v){

        if (strpos($v,"tag")!==false )
        {

            return true;
        }

    return false;

}




function css_class_tag_filter($classes){

    $tag_class = array_filter($classes, array($this,'removenontag'));

   
    
   
    return str_ireplace("tag-","",$tag_class);
}





         

  function filter_tag() {

    ?>

     <div id="portfolio-filters">
     <ul id="filters" class="nav nav-pills">

       <li><a href="#" data-filter="*">show all</a></li>
     <?php
        
        $options = get_option('portfolio_options');

        $filter_options = $options['tag_filter_select'];
        foreach($filter_options as $filter_option)
        {

               echo '<li><a href="#" data-filter=".'.$filter_option.'">'.$filter_option.'</a></li>';

        }
        
     ?>   

  <!-- 
  <li><a href="#" data-filter=".test">test</a></li>
  <li><a href="#" data-filter=".test3">test3</a></li>
  -->


     </ul>
     </div>

<?php
  }



  function display_work() {

    $options = get_option('portfolio_options');

   // var_dump($options);


      
       

   // if($options['tag_filter'])
      if($options['tag_filter_select'])
        $this->filter_tag();


    echo "<div id=\"portfolio\">\n"; 
    
    $query = array(
    	'tax_query' => array(
    		array(
    			     'taxonomy' => 'category',
    			     'field' => 'slug',
    			     'terms' =>  $options['category']
    			 )
    		)
    	);

    $the_query = new WP_Query($query);

    
    
    while($the_query->have_posts()){

         add_filter('post_class',array($this,'css_class_tag_filter'));
         $the_query->the_post();
         
         $class = 'work ' . implode(' ', get_post_class());

         var_dump($the_query->post);
         echo "<div class=\"$class\">\n";
         echo '<h1 class="headline">' . $the_query->post->post_title . '</h1>';
         //echo $the_query->post->post_content;
         echo '<a href="' . get_permalink($post->ID) . '" >';
         echo get_the_post_thumbnail($the_query->post->ID, 'thumbnail');  
         echo  '</a>';
         
         if ($the_query->post->post_excerpt) {
         echo '<div class="post_excerpt">' . $the_query->post->post_excerpt . '</div>';
         }
         echo '</div>';
        



        

    }
    
     echo '</div>';
     
     
   }
  }
  




$workpage = new Workpage();

?>