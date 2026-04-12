<?php
/**
 * Injeção de Scripts de Rastreamento (Google Analytics, Ads e Meta Pixel) no Head e Footer
 */

// Scripts do Cabeçalho (Head)
add_action('wp_head', function() {
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1NRXPK21JE"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  // Configuração (Analytics)
  gtag('config', 'G-1NRXPK21JE');
</script>

<!-- Meta Pixel Code DR-Pixel News -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '805935457817596');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=805935457817596&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<?php
}, 1);

// Scripts do Rodapé (Footer) - Vazio por enquanto, mas preparado
add_action('wp_footer', function() {
    // Insira scripts que devem carregar no final da página aqui
}, 100);
