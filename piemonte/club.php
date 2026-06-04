<?php $page='club'; $title='El Club — CD Piemonte Trasco'; $desc='Historia del CD Piemonte Trasco, equipo de fútbol nacido en la ESEI del Campus de Ourense.'; include 'includes/header.php'; ?>

<section class="block">
  <div class="container">
    <div class="section-head reveal">
      <span class="section-tag">// el club</span>
      <h2>Nuestra historia</h2>
      <p>Donde el balón se cruza con el código fuente.</p>
    </div>

    <div class="split">
      <div class="prose reveal">
        <p class="lead">El <strong>CD Piemonte Trasco</strong> no nació en un despacho ni en una federación. Nació en los pasillos de la <strong>ESEI</strong>, entre prácticas de programación, cafés con sueño y partidos improvisados en el descanso de clase.</p>
        <p>Somos alumnos y miembros de la Escuela de Ingeniería Informática del Campus de Ourense (Universidad de Vigo) que un día decidimos dar el salto: fundar un equipo de verdad, con escudo, con camiseta y con un lema gritado a pulmón —<em>"Un trasco no meu peito"</em>.</p>
        <p>Nuestro club mezcla dos mundos que parecen opuestos pero comparten lo esencial: <strong>disciplina, equipo y pasión por mejorar cada día</strong>. Un commit más, un sprint más, un gol más.</p>
        <p>Hoy somos una pequeña gran familia. Compañeros de carrera, exalumnos, profes que se apuntan y amigos que se unen al proyecto. Y lo mejor: esto solo acaba de empezar.</p>
      </div>

      <div class="reveal">
        <div class="code-block">
<span class="com">// historia.js</span><br>
<span class="kw">const</span> piemonte = {<br>
&nbsp;&nbsp;origen: <span class="str">'ESEI - Campus Ourense'</span>,<br>
&nbsp;&nbsp;universidad: <span class="str">'UVigo'</span>,<br>
&nbsp;&nbsp;lema: <span class="str">'Un trasco no meu peito'</span>,<br>
&nbsp;&nbsp;valores: [<span class="str">'pasión'</span>, <span class="str">'equipo'</span>, <span class="str">'código'</span>],<br>
&nbsp;&nbsp;run() { <span class="kw">return</span> <span class="str">'⚽ + 💻 = ❤️'</span>; }<br>
};
        </div>

        <div class="grid grid-3" style="margin-top:2rem;">
          <div class="card">
            <div class="card-icon">🎓</div>
            <h3>ESEI</h3>
            <p>Nuestra casa, nuestro origen.</p>
          </div>
          <div class="card">
            <div class="card-icon">🏆</div>
            <h3>Trasco</h3>
            <p>Coraje en gallego. Pura raza.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="block" style="background:var(--carbon);">
  <div class="container">
    <div class="section-head reveal">
      <span class="section-tag">// nuestros valores</span>
      <h2>Lo que nos mueve</h2>
    </div>
    <div class="grid grid-3">
      <div class="card reveal"><div class="card-icon">01</div><h3>Compañerismo</h3><p>Aquí no hay estrellas: hay un equipo. Lo que pasa en el vestuario, se queda en el vestuario.</p></div>
      <div class="card reveal"><div class="card-icon">02</div><h3>Identidad</h3><p>Representamos a la ESEI dentro y fuera del campo. Con respeto, con humor y con orgullo.</p></div>
      <div class="card reveal"><div class="card-icon">03</div><h3>Diversión</h3><p>Jugamos en serio, pero nos lo pasamos en grande. Sin eso, esto no tendría sentido.</p></div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
