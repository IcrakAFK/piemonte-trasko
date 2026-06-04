<?php $page='inicio'; $title='CD Piemonte Trasco — Un trasco no meu peito'; include 'includes/header.php'; ?>

<section class="hero">
  <div class="hero-inner">
    
    <div class="hero-logo-wrap reveal" style="margin-bottom: 1.5rem; display: flex; justify-content: center;">
      <img src="assets/img/logo.jfif" 
           alt="Escudo CD Piemonte Trasco" 
           style="width: 140px; height: 140px; object-fit: contain; border-radius: 24px; filter: drop-shadow(0 0 20px rgba(0, 255, 156, 0.3));">
    </div>

    <h1>CD Piemonte <span class="accent">Trasco</span></h1>
    <p class="hero-lema">// "Un trasco no meu peito"</p>
    <div class="hero-cta">
      <a href="plantilla.php" class="btn btn-primary">Conoce la plantilla →</a>
      <a href="https://instagram.com/piemonte_trasko" target="_blank" rel="noopener" class="btn btn-ghost">@piemonte_trasko</a>
    </div>
  </div>
</section>

<section class="block">
  <div class="container">
    <div class="section-head reveal">
      <span class="section-tag">// quiénes somos</span>
      <h2>Del aula al césped</h2>
      <p>Somos un equipo de fútbol aficionado nacido en los pasillos de la <strong>ESEI</strong>, el Campus de Ourense de la <strong>Universidad de Vigo</strong>. Compilamos jugadas, debuggeamos al rival y desplegamos victorias cada fin de semana.</p>
    </div>

    <div class="grid grid-3">
      <div class="card reveal">
        <div class="card-icon">{ }</div>
        <h3>Identidad ESEI</h3>
        <p>Nacidos entre líneas de código y partidos en el descanso entre clases. Llevamos la Escuela en el escudo.</p>
      </div>
      <div class="card reveal">
        <div class="card-icon">⚽</div>
        <h3>Fútbol de barrio</h3>
        <p>Aficionados, intensos y sin filtros. Jugamos por la camiseta, por los compañeros y por el orgullo universitario.</p>
      </div>
      <div class="card reveal">
        <div class="card-icon">&gt;_</div>
        <h3>Comunidad viva</h3>
        <p>Sigue convocatorias, previas y crónicas en nuestro Instagram <a href="https://instagram.com/piemonte_trasko" target="_blank" rel="noopener">@piemonte_trasko</a>.</p>
      </div>
    </div>
  </div>
</section>

<section class="sponsors-strip">
  <div class="container">
    <a class="sponsor-item" href="https://maps.google.com/?q=Hamburgueser%C3%ADa+Queen+Cami%C3%B1o+Caneiro+Ourense" target="_blank" rel="noopener" style="text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#00ff9c'" onmouseout="this.style.color='inherit'">
      Hamburguesería Queen
    </a>
    <a class="sponsor-item" href="https://maps.google.com/?q=J%C3%BApiter+Ourense+Cardenal+Quevedo" target="_blank" rel="noopener" style="text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#00ff9c'" onmouseout="this.style.color='inherit'">
      Júpiter Ourense
    </a>
    <a class="sponsor-item" href="https://instagram.com/cristina_kdk" target="_blank" rel="noopener" style="text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#00ff9c'" onmouseout="this.style.color='inherit'">
      Cristina KDK
    </a>
  </div>
</section>

<section class="block">
  <div class="container split">
    <div class="prose reveal">
      <span class="section-tag">// próximos partido</span>
      <h2 style="font-family:var(--font-display);font-size:2.5rem;letter-spacing:1px;color:var(--chalk);margin:.5rem 0 1.5rem;">Nos vemos en el campo</h2>
      <p class="lead">Consulta el calendario completo, la clasificación y los próximos rivales del Piemonte Trasco.</p>
      <p>Cada jornada es una nueva commit. Cada gol, un push directo a master.</p>
      <a href="calendario.php" class="btn btn-primary" style="margin-top:1rem;">Ver calendario →</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>