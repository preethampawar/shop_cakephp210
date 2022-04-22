<?php 
$this->set('homeLinkActive', true);

$keywords = ($this->Session->read('Site.meta_keywords')) ? $this->Session->read('Site.meta_keywords') : 'Plant Nursery,Buy plants online,Plant Nurseries';
$description = ($this->Session->read('Site.meta_description')) ? $this->Session->read('Site.meta_description') : $this->Session->read('Site.title').' is a online plant nursery store where you can buy plants and gardening items';
echo $this->Html->meta('keywords', $keywords, array('inline'=>false));
echo $this->Html->meta('description', $description, array('inline'=>false));

echo $this->element('slider');
?>
 <!-- main content -->
    <div id="homepage">
      <!-- Introduction -->
      <section id="intro" class="clear">
        <article class="one_fifth"><a href="#"><img src="img/images/demo/166x130.gif" width="166" height="130" alt=""></a>
          <h2>Indonectetus facilis</h2>
          <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna.</p>
          <footer class="more"><a href="#">Read More &raquo;</a></footer>
        </article>
        <article class="one_fifth"><a href="#"><img src="img/images/demo/166x130.gif" width="166" height="130" alt=""></a>
          <h2>Indonectetus facilis</h2>
          <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna.</p>
          <footer class="more"><a href="#">Read More &raquo;</a></footer>
        </article>
        <article class="one_fifth"><a href="#"><img src="img/images/demo/166x130.gif" width="166" height="130" alt=""></a>
          <h2>Indonectetus facilis</h2>
          <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna.</p>
          <footer class="more"><a href="#">Read More &raquo;</a></footer>
        </article>
        <article class="one_fifth"><a href="#"><img src="img/images/demo/166x130.gif" width="166" height="130" alt=""></a>
          <h2>Indonectetus facilis</h2>
          <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna.</p>
          <footer class="more"><a href="#">Read More &raquo;</a></footer>
        </article>
        <article class="one_fifth lastbox"><a href="#"><img src="img/images/demo/166x130.gif" width="166" height="130" alt=""></a>
          <h2>Indonectetus facilis</h2>
          <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna.</p>
          <footer class="more"><a href="#">Read More &raquo;</a></footer>
        </article>
      </section>
      <!-- / Introduction -->
      <!-- ########################################################################################## -->
      <!-- Services -->
      <section id="services" class="last clear">
        <article class="one_third">
          <figure class="clear"><img src="img/images/demo/48x48.gif" width="48" height="48" alt="">
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam etiam ultrices suspen disse in justo eu magna.</p>
            </figcaption>
          </figure>
        </article>
        <article class="one_third">
          <figure class="clear"><img src="img/images/demo/48x48.gif" width="48" height="48" alt="">
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam etiam ultrices suspen disse in justo eu magna.</p>
            </figcaption>
          </figure>
        </article>
        <article class="one_third lastbox">
          <figure class="clear"><img src="img/images/demo/48x48.gif" width="48" height="48" alt="">
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam etiam ultrices suspen disse in justo eu magna.</p>
            </figcaption>
          </figure>
        </article>
        <article class="one_third">
          <figure class="clear"><img src="img/images/demo/48x48.gif" width="48" height="48" alt="">
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam etiam ultrices suspen disse in justo eu magna.</p>
            </figcaption>
          </figure>
        </article>
        <article class="one_third">
          <figure class="clear"><img src="img/images/demo/48x48.gif" width="48" height="48" alt="">
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam etiam ultrices suspen disse in justo eu magna.</p>
            </figcaption>
          </figure>
        </article>
        <article class="one_third lastbox">
          <figure class="clear"><img src="img/images/demo/48x48.gif" width="48" height="48" alt="">
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam etiam ultrices suspen disse in justo eu magna.</p>
            </figcaption>
          </figure>
        </article>
      </section>
      <!-- / Services -->
    </div>
    <!-- / content body -->
  