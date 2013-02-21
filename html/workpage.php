<?php
class WorkPage{

  function __construct()
  {
            add_shortcode( 'oppp', array($this,'portfolio_page_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'load_js'));
            add_action( 'wp_enqueue_scripts', array($this,'portfolio_stylesheet') );
          //  add_action('wp_enqueue_scripts', array($this, 'load_isotope_middle_js'));
           // add_action('wp_enqueue_scripts', array($this, 'load_isotope_setting_js'));
            add_action( 'wp_footer', array($this,'load_isotope_middle_js') );
            add_action( 'wp_footer', array($this,'load_isotope_setting_js') );
          
  }


  function portfolio_stylesheet() {
    wp_enqueue_style( 'portfoliostyle', plugins_url( 'css/one-page-portfolio-plugin.css', dirname(__FILE__) ) );
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

var $work = $('#oppp-portfolio');

$work.isotope({
  // options
  itemSelector : '.oppp-work-content',
  layoutMode : 'fitRows'
 
  
});

// filter items when filter link is clicked
$('#oppp-filters a').click(function(){
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

    $tag_class_remove_blank = str_ireplace(" ","-",$tag_class);   
    
   
    return str_ireplace("tag-","oppp-",$tag_class_remove_blank);
}





         

  function filter_tag() {

    ?>

     <div id="oppp-portfolio-filters">
     <ul id="oppp-filters" class="nav nav-pills">

       <li><a href="#" data-filter="*">show all</a></li>
     <?php
        
        $options = get_option('oppp_portfolio_options');

        $filter_options = $options['tag_filter_select'];
        foreach($filter_options as $filter_option)
        {

               $filter_option_remove_blank = str_ireplace(" ","-",$filter_option);   

               echo '<li><a href="#" data-filter=".'.$filter_option_remove_blank.'">'.substr_replace($filter_option,"",0,5).'</a></li>';

        }
        
     ?>   

  <!-- 
  <li><a href="#" data-filter=".test">test</a></li>
  <li><a href="#" data-filter=".test3">test3</a></li>
  -->


     </ul>
     </div>
     <br>

<?php
  }



  function display_work() {

    $options = get_option('oppp_portfolio_options');

   // var_dump($options);


      
       

   // if($options['tag_filter'])
      if($options['tag_filter_select'])
        $this->filter_tag();


    echo "<div id=\"oppp-portfolio\">\n"; 
    
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
         
         $class = 'oppp-work-content ' . implode(' ', get_post_class());

        
         echo "<div class=\"$class\">\n";
         if($options['post_title_display'])
         echo '<h1 class="headline">' . $the_query->post->post_title . '</h1>';
         //echo $the_query->post->post_content;
         echo '<a href="' . get_permalink($post->ID) . '" >';
         echo get_the_post_thumbnail($the_query->post->ID, $options['thumbnail_size']);  
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