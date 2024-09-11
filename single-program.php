<?php

get_header();

while(have_posts())
{
    the_post();
    pageBanner();
    
    ?>

    <div class="container container--narrow page-section">

<div class="generic-content">
    
<div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link"
           href="<?php echo get_post_type_archive_link('program') ;?>">
          <i class="fa fa-home" aria-hidden="true">

          </i>
           All Programs</a> 
           <span class="metabox__main">
           <?php the_title();?>
        </span>
        </p>
      </div>
</div>

<?php
the_field('main_body_content')
?>

<?php  $relatedProfessor=new WP_Query(array(
            'posts_per_page' => -1,
            'post_type'=>'professor',
             'orderby'=>'title',
             'order'=>'ASC',
             'meta_query'=>array (
              array(
              'key'=>'related_programs',
              'compare'=>'LIKE',
              'value'=>'"'. get_the_ID().'"'
            //  because in wordpress datas are not saved in database as an normal array the array is saved as an string
            // thats why we will get the id and put it in a '' so that when it will search can use the id as string and get the result
            
              
              )
             )
          )); 
         if( $relatedProfessor->have_posts()){
            echo '<hr class="section-break">';
            echo '<h2 class=" headline headline--medium ">
              ' . get_the_title() .' Professor(s) </h2>';
           echo '<ul class="professor-cards">';
              while($relatedProfessor->have_posts()){
  
              $relatedProfessor-> the_post(); ?>
               <li class='professor-card__list-items'>
                <a class="professor-card" href=<?php the_permalink()?>>
               <img class='professor-card__image' 
               src="  <?php 
                  the_post_thumbnail_url('professorLandscape')
                ?>" alt="">
               <span class='professor-name'>  <?php 
                 the_title()
                         ?></span>
             </a></li>
  
              <?php  }
              echo '<ul>';
         };


wp_reset_postdata();
//wp_reset_postdata(); we used it here  to get the title or other information of the page 

// we are in instead of the professor or as we used in the most recent query

          $homepageEvents=new WP_Query(array(
            'post_type'=>'event',
            'meta_key'=>'event_date',
             'orderby'=>'meta_value_num',
             'order'=>'ASC',
             'meta_query'=>array (
              array(
              'key'=>'event_date',
              'compare'=>'>=',
      
              'type'=>'numeric'
              ),
              // the first array will show the events thats come after the date of today and the second
              // array will show the events that are related to the program
              array(
              'key'=>'related_programs',
              'compare'=>'LIKE',
              'value'=>'"'. get_the_ID().'"'
            //  because in wordpress datas are not saved in database as an normal array the array is saved as an string
            // thats why we will get the id and put it in a '' so that when it will search can use the id as string and get the result
            
              
              )
             )
          )); 
         if( $homepageEvents->have_posts()){
            echo '<hr class="section-break">';
            echo '<h2 class=" headline headline--medium "> Upcomming ' . get_the_title() .' Event </h2>';
            while($homepageEvents->have_posts()){
  
              $homepageEvents-> the_post();
              get_template_part('template-parts/content','event');
            }
         }
          
          ?>
</div>
    </div>

    <?php
};
get_footer()
?>