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