<?php
/**
 * Search Form Template
 * Mantém o mesmo estilo dos templates anteriores com foco em mobile first
 */
?>
<div class="search-bar">
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" 
               placeholder="Buscar receitas..." 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               title="Buscar">
        <button type="submit">Buscar</button>
    </form>
</div>
