<?php get_header();

$semana_post = get_post_meta(get_the_ID(), '_semana', true);
$semana_atual = semana_atual();

/* BLOQUEIO TOTAL */
if ($semana_post != $semana_atual) {
    wp_redirect(home_url('/cardapio-da-semana/'));
    exit;
}

$conteudo = get_post_meta(get_the_ID(), '_conteudo', true);
?>

<main id="cardapio-app" itemscope itemtype="https://schema.org/Menu">

<style>
/* === MOBILE FIRST / CWV === */
body{margin:0;background:#f7f7f7;font-family:system-ui}
.card{background:#fff;border-radius:14px;margin:12px;padding:16px}
h1{font-size:1.3rem}
img{width:100%;height:auto;border-radius:10px}
.ads{margin:16px}
</style>

<h1 class="card">🍽 Cardápio da Semana <?= esc_html($semana_post); ?></h1>

<?= $conteudo; ?>

<!-- BLOCO DE ANÚNCIO -->
<div class="ads card" aria-label="Publicidade">
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-XXXX"
     data-ad-slot="XXXX"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
</div>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>

<!-- SCHEMA IA -->
<script type="application/ld+json">
{
 "@context": "https://schema.org",
 "@type": "Menu",
 "name": "Cardápio da Semana",
 "provider": {
   "@type": "Organization",
   "name": "Descomplicando Receitas",
   "url": "https://descomplicandoreceitas.com.br"
 }
}
</script>

</main>

<?php get_footer(); ?>
